<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Model\Article;
use AppBundle\Model\Author;

define('BLOG_PATH', 'data/blog.json');

class DefaultController extends Controller
{

    public function __construct()
    {

    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $articles = $this->loadBlog(BLOG_PATH);

        return $this->render(
            "default/blog.html.twig",
            ["articles"=>$articles]
            );
    }

    /**
     * TODO
     */
    public function loadDBBlog():array{
        $data = file_get_contents($path);
        return $this->parse($data);
    }

    public function loadBlog($path):array{
        $data = file_get_contents($path);
        return $this->parse($data);
    }

    public function parse(String $data):array{
        $rawData=json_decode($data, true);
        $rawAuthors = $rawData['authors'];
        $authors = array_map(function ($rawAuthor) {
            return new Author($rawAuthor['id'], $rawAuthor['firstname'], $rawAuthor['lastname']);
        }, $rawAuthors);

        $rawArticles = $rawData['articles'];

        // pour chaque rawArticle on veut récup une instance de Article
        $articles = array_map(function ($rawArticle) use($authors){

            $articleAuthorId = $rawArticle["authorId"];

            $articleAuthors = array_filter(
                $authors,function($author) use($articleAuthorId){
                return $author->id == $articleAuthorId;
            });

            $articleAuthor = current($articleAuthors);

            return new Article(
                $rawArticle["id"], $rawArticle["title"],
                $rawArticle["content"], $articleAuthor,
                new \DateTime($rawArticle['date'])
            );
        }, $rawArticles);
        return $articles;
    }

    /**
     * @Route("/details/{articleId}", name="articleDetails")
     */
    public function detailsAction($articleId){
        $selectedArticle = current( array_filter(
            $this->loadBlog(BLOG_PATH),
            function($article) use($articleId) {
                return $articleId == $article->id;
            }));

        return $this->render('default/details.html.twig',
            ["article"=>$selectedArticle]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        $value = $request->get('value');
        return new Response('test'.$value);
    }

    /**
     * @Route("/twig", name="twig")
     */
    public function twigAction(Request $request)
    {
        return $this->render("default/index.html.twig");
    }



    /**
     * @Route("/salut/{nom}/{prenom}", name="salut")
     */
    public function salutAction($prenom, $nom)
    {
        return new Response("salut $prenom $nom");
    }

    /**
     * @Route("/user/{userId}/profile", name="profile")
     */
    public function userProfileAction($userId)
    {
        $user = [
            "id"=>123, "nom"=>"dupond"
            , "prenom"=> "joe"];
        return new Response(json_encode($user));
    }

    /**
     * @Route("/user/{userId}/avatar", name="avatar")
     */
    public function userAvatarAction($userId)
    {
        return new Response('<:)');
    }
}




