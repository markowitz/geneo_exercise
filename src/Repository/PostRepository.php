<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, TagRepository $tagRepo, ImagesRepository $imageRepo)
    {
        parent::__construct($registry, Post::class);

        $this->tagRepo = $tagRepo;
        $this->imageRepo = $imageRepo;
    }

     /**
     * Create post
     * @param [type] $dto
     * @param [type] $author
     *  @param [type] $images
     * @return Post $post
     */
    public function create(Object $dto, Object $user, array $images = [])
    {

        $post = new Post();
        $post->setTitle($dto->title);
        $post->setContent($dto->content);
        $post->setAuthor($user);
        $tags = $dto->postTags($dto->tags);

        $this->addTag($post, $tags);
        $this->addImages($post, $images);

        $this->_em->persist($post);
        $this->_em->flush();

        return $post;
    }

     /**
     * Get  Posts by author  and other authors following
     * @return Object $user
     */
    public function fetch(Object $user)
    {
        $query = $this->createQueryBuilder('post')
                        ->select('post')
                        ->join('post.author', 'user')
                        ->leftJoin('user.followers', 'uf')
                        ->where('post.is_published = 1')
                        ->andWhere('user = :user OR post.author IN(:userfollowing)')
                        ->setParameter('user', $user)
                        ->setParameter('userfollowing', $user->getFollowing())
                        ->orderBy('post.created_at', 'DESC')
                        ->getQuery()
                        ->getResult();

            return $query;
    }

     /**
     * Get Single Post by author and post slug
     * @param String $slug
     * @return Post $post
     */
    public function getSinglePost($slug)
    {
        $query = $this->createQueryBuilder('post')
                        ->select('post')
                        ->where('post.slug = :slug')
                        ->where('post.is_published = 1')
                        ->setParameter('slug', $slug)
                        ->getQuery()
                        ->getOneOrNullResult();

            return $query;
    }

    /**
     * Get pending posts
     * @return Collection $posts
     */
    public function fetchPendingPosts()
    {
        $query = $this->createQueryBuilder('post')
                        ->select('post')
                        ->where('post.is_published = 0')
                        ->orderBy('post.created_at', 'DESC')
                        ->getQuery()
                        ->getResult();

            return $query;
    }

    /**
     * approve/disapprove a post
     * @param Post $post
     * @param String $status
     */
    public function approval(Post $post, $status)
    {
        $post->setIsPublished($status);

        $this->_em->persist($post);
        $this->_em->flush();
    }

    /**
     * update post
     * @param Post $post
     * @param Object $dto
     * @param Object $user
     * @param Array $imageDtos
     */
    public function update(Post $post, $dto, Object $user, $imageDtos)
    {
        $post->setTitle($dto->title);
        $post->setContent($dto->content);
        $post->setAuthor($user);
        $this->addImages($post, $imageDtos);

        $this->_em->persist($post);
        $this->_em->flush();

        return $post;
    }

    /**
     * @param Post $post
     * @param Array $images
     */
    public function addImages($post, $images)
    {
        if (count($images)) {

            foreach($images as $image) {

                $newImage = new Images();
                $newImage->setFileName($image->file_name);
                $newImage->setFilePath($image->file_path);
                $post->addImage($newImage);
                $this->_em->persist($newImage);

            }
        }
    }

    /**
     * @param Post $post
     * @param Array $tags
     */
    public function addTag($post, $tags)
    {
        if (count($tags)) {
            foreach($tags as $tag) {
                $tagExist = $this->tagRepo->findOneBy(['name' => $tag->getName()]);

                if ($tagExist ) {
                    $post->getTags()->removeElement($tag);
                    $post->addTag($tagExist);
                } else {
                    $post->addTag($tag->name);
                    $this->_em->persist($tag);
                }

            }
        }
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}