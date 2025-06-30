<?php

function get_posts($posts) {
    if (!is_array($posts) || empty($posts)) {
        echo "<p>No posts available.</p>";
        return;
    }

    foreach ($posts as $post) {
        if (!is_array($post)) {
            continue; 
        }

        $topic   = isset($post["topic"]) && $post["topic"] !== "" 
                   ? htmlspecialchars($post["topic"], ENT_QUOTES, 'UTF-8') 
                   : null;

        $title   = htmlspecialchars($post["title"] ?? "No Title", ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($post["content"] ?? "", ENT_QUOTES, 'UTF-8');
        $likes   = is_numeric($post["likes"] ?? null) ? (int)$post["likes"] : 0;
        $creator = htmlspecialchars($post["creator"] ?? "anonymous", ENT_QUOTES, 'UTF-8');
        $id      = htmlspecialchars($post["id"] ?? "", ENT_QUOTES, 'UTF-8');

        echo "<form class='post-form' method='POST'>";

        if ($topic) {
            echo "<strong>c/</strong> {$topic}<br>";
        }

        echo "<strong></strong> {$title}<br>
              <p><strong>Post:</strong><br>
              <textarea readonly rows='5'>{$content}</textarea></p>
              <strong>Likes:</strong> {$likes}<br>
              <strong>Creator:</strong> {$creator}<br>
              <input type='hidden' name='id' value='{$id}'>
              <input type='hidden' name='community_name' value='" . ($topic ?? "") . "'>
              <input type='submit' name='action' value='Like'>
              <input class='submitbutton' id='viewbutton' type='submit' name='action' value='View Creator'>
              <input type='hidden' name='creator' value='{$creator}'>
              </form><hr>";
    }
}
?>
