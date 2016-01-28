# Simple Paginator

Here is code for a simple article paginator (you dont need an entire lib just for pagination).
When you are working with Symfony you can handle things much more easier. The Following is needed
to create a simple paginator.


[http://getbootstrap.com/components/#pagination](http://getbootstrap.com/components/#pagination)

# The Article Page

Here is a simple page written with Twig Template Engine. This shows a set of articles and a paginator
right bellow the article's set.

``` html
{% extends "_layouts/default.html.twig" %}

{% block content %}

{% if articles != null %}
  <div class="row">
  	{% for article in articles %}
        <div class="col-md-4 portfolio-item">
      		<div class="thumbnail">
            <!-- <img class="img-responsive" src="//placehold.it/700x400" alt="{{ article.title|title }}" /> -->
      			<div class="caption">
      				<h1>
      					{{ article.title|title }}
      					<br>
      					<small>{{ article.subtitle|title }}</small>
      				</h1>
      				<p class="description">Date: {{ article.date|date('d/m/y') }}</p>
      				<p class="description text-justify">Abstract: {{ article.abstract|md2html }}</p>
      				<p class="note">Category: {{ article.codeCategory.name }}</p>
      				<p>
      					<a href="{{ url('importArticle', id: article.id }) }}"
      					   class="btn btn-block btn-success" role="button">Read</a>
      				</p>
      			</div>
      		</div>
        </div>
		{% endfor %}
  </div>

  <div class="row text-center">
      <div class="col-lg-12">
          <ul class="pagination">
              {% for i in pages %}
                {% if currentLoadMore == i %}
                  <li class="active">
                    <a href="{{ path('general_articles_category', { categoria: currentCategory, loadmore: i }) }}">
                      {{ i }}
      		    </a>
                  </li>
                {% else %}
                  <li>
                    <a href="{{ path('general_articles_category', { categoria: currentCategory, loadmore: i }) }}">
                      {{ i }}
      	            </a>
                  </li>
                {% endif %}
              {% endfor %}
          </ul>
      </div>
  </div>

{% else %}
  <p class="description">We couldnt load articles from this category yet.</p>
{% endif %}

{% endblock %}
```

## The PHP Code

See that I'm working here with Doctrine for fetching from database. Now the code above
maps a simple route that works with 2 parameters, the first one is category and the
second one is the loadmore parameter. Now just what you have to do is work if these
parameters in the presentation layer showing for the user the ways to see articles.

``` php
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
```

## Credits

Thanks, I have seeing to much people wasting time learning hard way to do simple
paginations. When we have bootstrap, symfony, doctrine in the tool's bag.
