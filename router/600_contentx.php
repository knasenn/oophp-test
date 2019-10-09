<?php

// Get essentials
require "../src/contentx/autoload.php";
require "../src/contentx/config.php";
require "../src/contentx/function.php";
require "../src/contentx/Database.php";
require "../src/contentx/MyTextFilter.php";

// Get incoming
$route = getGet("route", "");

// General variabels (available to the views)
$titleExtended = " | My Movie Database";
$view = [];

$db = new \Aiur\Contentx\Database();
$sql = null;
$resultset = null;

$app->db->connect($databaseConfig);







/**
 * Show error GET?
 */
$app->router->get("contentx/error", function () use ($app) {
    $title = "Error";



    $data = [
    ];

    $app->page->add("contentx/error", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Show all content GET?
 */
$app->router->get("contentx", function () use ($app) {
    $title = "Show all movies";
    $view[] = "view/show-all.php";
    $sql = "SELECT * FROM content;";
    $res = $app->db->executeFetchAll($sql);

    $data = [
        "resultset" => $res,
    ];

    $app->page->add("contentx/show-all", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Show admin GET?
 */
$app->router->get("admin", function () use ($app) {
    $title = "Admin";
    $view[] = "view/admin.php";
    $sql = "SELECT * FROM content;";
    $res = $app->db->executeFetchAll($sql);

    $data = [
        "resultset" => $res,
    ];

    $app->page->add("contentx/admin", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});



/**
 * Show create GET?
 */
$app->router->get("create", function () use ($app) {
    $title = "Show Create";
    $view[] = "view/create.php";

    $app->page->add("contentx/create");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Show create POST?
 */
$app->router->post("create", function () use ($app) {
    $doDelete = getPost("doDelete");

    if (getPost("doSave") == null && $doDelete != "deleted") {
        echo "created";
        $title = "Edit Create";
        $contentTitle = getPost("contentTitle");
        $sql = "INSERT INTO content (title) VALUES (?);";
        $app->db->execute($sql, [$contentTitle]);
        $contentId = $app->db->lastInsertId();

        $sql = "SELECT * FROM content WHERE id = ?;";
        $content = $app->db->executeFetch($sql, [$contentId]);

        $data = [
            "content" => $content,
        ];

        $app->page->add("contentx/edit-create", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } elseif ($doDelete == "deleted") {
        echo "deleted date";
        $contentId = getPost("contentId");
        $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
        $app->db->execute($sql, [$contentId]);

        return $app->response->redirect("admin");
    } else {
        // FIXA
        //CHECKING FOR SLUG AND PATH IS OK
        $slug = getPost("contentSlug");
        $path = getPost("contentPath");
        if ($path == "") {
            $path = rand();
        }

        if ($slug == "") {
            $slug = rand();
        }

        $sqlSlug = "SELECT * FROM content WHERE slug LIKE ?;";
        $testSlug = $app->db->executeFetch($sqlSlug, [$slug]);

        $sqlPath = "SELECT * FROM content WHERE path LIKE ?;";
        $testPath = $app->db->executeFetch($sqlPath, [$path]);

        if ($testSlug != null) {
            if ($testSlug->slug != null) {
                return $app->response->redirect("contentx/error");
            }
        }

        if ($testPath != null) {
            if ($testPath->path != null) {
                return $app->response->redirect("contentx/error");
            }
        }
        //UPDATING
        $params = [
            getPost("contentTitle"),
            $path,
            $slug,
            getPost("contentData"),
            getPost("contentType"),
            getPost("contentFilter"),
            getPost("contentPublish"),
            getPost("contentId"),
        ];
        $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
        $app->db->execute($sql, array_values($params));
        return $app->response->redirect("contentx");
    }
});


/**
 * Show edit GET?
 */
$app->router->get("edit", function () use ($app) {
    $contentId = getGet("contId");
    $sql = "SELECT * FROM content WHERE id = ?;";
    $content = $app->db->executeFetch($sql, [$contentId]);

    $title = "Show edit";
    $data = [
        "content" => $content
    ];

    $app->page->add("contentx/edit", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Show edit POST?
 */
$app->router->post("edit", function () use ($app) {
    $contentId = getPost("contentId");
    $doSave = getPost("doSave");
    $doDelete = getPost("doDelete");

    if ($doSave) {
        //CHECKING FOR SLUG AND PATH IS OK
        $contentIdGet = getGet("contId");

        $sqlGet = "SELECT * FROM content WHERE id = ?;";
        $contentGet = $app->db->executeFetch($sqlGet, [$contentIdGet]);


        $slug = getPost("contentSlug");
        $path = getPost("contentPath");

        if ($path == "") {
            $path = rand();
        }

        $sqlSlug = "SELECT * FROM content WHERE slug LIKE ?;";
        $testSlug = $app->db->executeFetch($sqlSlug, [$slug]);

        $sqlPath = "SELECT * FROM content WHERE path LIKE ?;";
        $testPath = $app->db->executeFetch($sqlPath, [$path]);

        //TESTAR OM DUBBLETT(OM EJ EDIT)
        if ($testSlug->slug != null && $testSlug->slug != $contentGet->slug) {
            return $app->response->redirect("contentx/error");
        }

        if ($testPath->path != null && $testSlug->path != $contentGet->path) {
            return $app->response->redirect("contentx/error");
        }
        //UPDATEING
        echo "saved";
        $params = [
            getPost("contentTitle"),
            getPost("contentPath"),
            getPost("contentSlug"),
            getPost("contentData"),
            getPost("contentType"),
            getPost("contentFilter"),
            getPost("contentPublish"),
            getPost("contentId"),
        ];
        $sql = "UPDATE content SET title=?, path=?, slug=?, data=?, type=?, filter=?, published=? WHERE id = ?;";
        $app->db->execute($sql, array_values($params));

        $title = "Edit";
        $contentId = getPost("contentId");
        $sql = "SELECT * FROM content WHERE id = ?;";
        $content = $app->db->executeFetch($sql, [$contentId]);
        return $app->response->redirect("admin");
    } elseif ($doDelete == "deleted") {
        $title = "Delete";
        $contentId = getPost("contentId");
        $sql = "SELECT * FROM content WHERE id = ?;";
        $content = $app->db->executeFetch($sql, [$contentId]);

        $data = [
            "content" => $content,
        ];

        $app->page->add("contentx/delete", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } elseif ($doDelete == "deleted-date") {
        $contentId = getPost("contentId");
        $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
        $app->db->execute($sql, [$contentId]);

        return $app->response->redirect("admin");
    }
});


/**
 * Show delete GET?
 */
$app->router->get("delete", function () use ($app) {
    $contentId = getGet("contId");

    $sql = "SELECT * FROM content WHERE id = ?;";
    $content = $app->db->executeFetch($sql, [$contentId]);

    $title = "Show edit";
    $data = [
        "content" => $content
    ];

    $app->page->add("contentx/delete", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});


  /**
   * Show delete POSt?
   */
  $app->router->post("delete", function () use ($app) {
      $contentId = getPost("contentId");
      $sql = "UPDATE content SET deleted=NOW() WHERE id=?;";
      $app->db->execute($sql, [$contentId]);

      return $app->response->redirect("admin");
  });



  /**
   * Show page GET?
   */
  $app->router->get("pagesx", function () use ($app) {
    //TESTING IF ROUTE IS SET
    $testPath = getGET("route");

    if ($testPath == null) {
        $title = "View pages";
        $view[] = "view/pages.php";

        $sql = <<<EOD
SELECT
    *,
    CASE
        WHEN (deleted <= NOW()) THEN "isDeleted"
        WHEN (published <= NOW()) THEN "isPublished"
        ELSE "notPublished"
    END AS status
FROM content
WHERE type=?
;
EOD;
        $resultset = $app->db->executeFetchAll($sql, ["page"]);

        $data = [
            "resultset" => $resultset
        ];

        $app->page->add("contentx/pages", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } else {
        //DOES THIS IF ROUTE IS SET
        $route = getGet("route");
        $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS modified_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS modified
FROM content
WHERE
    path = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
;
EOD;
        $content = $app->db->executeFetch($sql, [$route, "page"]);
        $filter = new \Aiur\Contentx\MyTextFilter();
        $text = $content->data;
        $html = $filter->parse($text, ["markdown","bbcode", "link", "nl2br"]);
        $content->data = $html;

        $title = "Show edit";
        $data = [
            "content" => $content
        ];

        $app->page->add("contentx/page", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    }
  });



  /**
   * Show blog GET?
   */
  $app->router->get("blogx", function () use ($app) {
      // Oversigt over blogposts
      $testPath = getGET("route");

    if ($testPath == null) {
        $title = "View blog";
        $view[] = "view/blog.php";

        $sql = <<<EOD
SELECT
  *,
  DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
  DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE type=?
ORDER BY published DESC
;
EOD;
        $resultset = $app->db->executeFetchAll($sql, ["post"]);

        $data = [
            "resultset" => $resultset
        ];

        $app->page->add("contentx/blog", $data);

        return $app->page->render([
            "title" => $title,
        ]);
    } else {
      // Shows blogpost
            $sql = <<<EOD
SELECT
    *,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%dT%TZ') AS published_iso8601,
    DATE_FORMAT(COALESCE(updated, published), '%Y-%m-%d') AS published
FROM content
WHERE
    slug = ?
    AND type = ?
    AND (deleted IS NULL OR deleted > NOW())
    AND published <= NOW()
ORDER BY published DESC
;
EOD;
            $route = getGET("route");
            $slug = substr($route, 5);
            $content = $app->db->executeFetch($sql, [$slug, "post"]);

            $filter = new \Aiur\Contentx\MyTextFilter();
            $text = $content->data;
            $html = $filter->parse($text, ["markdown","bbcode", "link", "nl2br"]);
            $content->data = $html;

            $title = $content->title;
            $view[] = "view/blogpost.php";

            $data = [
                "content" => $content
            ];

            $app->page->add("contentx/blogpost", $data);

            return $app->page->render([
                "title" => $title,
            ]);
    }
  });
