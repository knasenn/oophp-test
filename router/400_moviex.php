<?php

// namespace Aiur\Moviex;

//LÄGG TILL?
// namespace Aiur\Moviex;

// Get essentials
require "src/autoload.php";
require "src/config.php";
require "src/function.php";
require "src/Databasex.php";

// Get incoming
$route = getGet("route", "");

// General variabels (available to the views)
$titleExtended = " | My Movie Database";
$view = [];
$db = new \Aiur\Moviex\Database();
$sql = null;
$resultset = null;

$app->db->connect($databaseConfig);


/**
 * Show all movies?
 */
$app->router->get("moviex", function () use ($app) {
    $title = "Show all movies";
    $view[] = "view/show-all.php";
    $sql = "SELECT * FROM movie;";
    $res = $app->db->executeFetchAll($sql);


    $data = [
        "resultset" => $res,
    ];

    $app->page->add("moviex/show-all", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Show all movies?
 */
$app->router->get("reset", function () use ($app) {
    $title = "Reset movies";



    $data = [
    ];

    $app->page->add("moviex/reset", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Show title movies?
 */
$app->router->get("moviex-title", function () use ($app) {
    $title = "SELECT * WHERE title";
    $view[] = "view/search-title.php";
    $view[] = "view/show-all.php";
    $searchTitle = getGet("searchTitle");

    if ($searchTitle != null) {
        echo "tit";
        $sql = "SELECT * FROM movie WHERE title LIKE ?;";
        $res = $app->db->executeFetchAll($sql, [$searchTitle]);
        $data = [
            "resultset" => $res,
        ];

        $app->page->add("moviex/search-title", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } else {
        echo "tut";
        $title = "Show search movies";
        $view[] = "view/search-title.php";
        $sql = "SELECT * FROM movie;";
        $res = $app->db->executeFetchAll($sql);


        $data = [
            "resultset" => $res,
        ];

        $app->page->add("moviex/search-title", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    }
});

/**
 * Show year movies?
 */
$app->router->get("moviex-year", function () use ($app) {
    $title = "SELECT * WHERE year";
    $view[] = "view/search-year.php";
    $view[] = "view/show-all.php";
    $year1 = getGet("year1");
    $year2 = getGet("year2");


    if ($year1 && $year2) {
            echo "tit o tut";
            $sql = "SELECT * FROM movie WHERE year >= ? AND year <= ?;";
            $res = $app->db->executeFetchAll($sql, [$year1, $year2]);
            $data = [
                "resultset" => $res,
            ];

            $app->page->add("moviex/search-year", $data);

            return $app->page->render([
                "title" => $title,
            ]);
    } elseif ($year1) {
            echo "tit";
            $sql = "SELECT * FROM movie WHERE year >= ?;";
            $res = $app->db->executeFetchAll($sql, [$year1]);
            $data = [
                "resultset" => $res,
            ];

            $app->page->add("moviex/search-year", $data);

            return $app->page->render([
                "title" => $title,
            ]);
    } elseif ($year2) {
            echo "tut";
            $sql = "SELECT * FROM movie WHERE year <= ?;";
            $res = $app->db->executeFetchAll($sql, [$year2]);
            $data = [
                "resultset" => $res,
            ];

            $app->page->add("moviex/search-year", $data);

            return $app->page->render([
                "title" => $title,
            ]);
    } else {
            echo "bingo bango";
            $title = "Show year movies";
            $view[] = "view/search-year.php";
            $sql = "SELECT * FROM movie;";
            $res = $app->db->executeFetchAll($sql);


            $data = [
                "resultset" => $res,
            ];

            $app->page->add("moviex/search-year", $data);

            return $app->page->render([
                "title" => $title,
            ]);
    }
});


/**
 * Select movies?
 */
$app->router->get("moviex-select", function () use ($app) {
      $movieId = getPost("movieId");

      $title = "Select a movie";
      $view[] = "view/moviex-select.php";
      $sql = "SELECT id, title FROM movie;";
      $movies = $app->db->executeFetchAll($sql);


      $data = [
          "movies" => $movies,
      ];

      $app->page->add("moviex/moviex-select", $data);

      return $app->page->render([
          "title" => $title,
      ]);
});


/**
 * Select movies POST?
 */

$app->router->post("moviex-select", function () use ($app) {
    $title = "edit & add";
    $movieId = getPost("movieId");

    if (getPost("doDelete")) {
          echo "string1 delete";
          $sql = "DELETE FROM movie WHERE id = ?;";
          $app->db->execute($sql, [$movieId]);
          return $app->response->redirect("moviex-select");
    } elseif (getPost("doAdd")) {
          $sql = "INSERT INTO movie (title, year, image) VALUES (?, ?, ?);";
          $app->db->execute($sql, ["A title", 2017, "img/noimage.png"]);
          $movieId = $app->db->lastInsertId();

          $sql = "SELECT * FROM movie WHERE id = ?;";
          $movie = $app->db->executeFetchAll($sql, [$movieId]);
          $movie = $movie[0];

          $data = [
              "movie" => $movie,
          ];

          $app->page->add("moviex/movie-edit", $data);

          return $app->page->render([
              "title" => $title,
          ]);
    } elseif (getPost("doEdit") && is_numeric($movieId)) {
        // echo "string3 edit";
        $sql = "SELECT * FROM movie WHERE id = ?;";
        $movie = $app->db->executeFetchAll($sql, [$movieId]);
        $movie = $movie[0];


        $data = [
            "movie" => $movie,
        ];

        $app->page->add("moviex/movie-edit", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } elseif (getPost("doSave")) {
        // echo "string4 save";
        $movieId = getPost("movieId");
        $movieTitle = getPost("movieTitle");
        $movieYear = getPost("movieYear");
        $movieImage = getPost("movieImage");

        $sql = "UPDATE movie SET title = ?, year = ?, image = ? WHERE id = ?;";
        $app->db->execute($sql, [$movieTitle, $movieYear, $movieImage, $movieId]);

        return $app->response->redirect("moviex-select");
    }
});
