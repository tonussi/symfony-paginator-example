/**
 * @Route("/category", name="general_articles", defaults={"loadmore" = 2, "category" = 1})
 * @Route("/category/", name="general_articles", defaults={"loadmore" = 2, "category" = 1})
 * @Route("/category/{category}/{loadmore}", name="general_articles_category", requirements={"loadmore" = "\d+", "category" = "\d+"}, defaults={"loadmore" = 2, "category" = 1})
 */
public function showGeneralArticlesAction($category, $loadmore)
{
    $articles = $this->getDoctrine()
                     ->getRepository('AppBundle:Publication')
                     ->findBy(array('codeCategory' => $category,
                                    'articleState' => 'VISIBLE'), array('id' => 'DESC'), $loadmore, 0);

    $countHowMuchArticlesOnThisCategory = count($articles);
    $pagesArray = array();
    $pagesArray[0] = 1;
    $simpleMathToMapLoads = ceil($countHowMuchArticlesOnThisCategory / 10);
    for ($i = 1; $i < $simpleMathToMapLoads; $i++) {
        array_push($pagesArray, $i*2);
    }

    return $this->render('articles/articles.html.twig', array(
        'articles' => $articles,
        'pages' => $pagesArray,
        'currentCategory' => $category,
        'currentLoadMore' => $loadmore
    ));
}
