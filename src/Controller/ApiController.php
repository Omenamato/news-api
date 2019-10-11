<?php
namespace App\Controller;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;

class ApiController extends AppController {
    protected $url = 'https://newsapi.org/v2/everything'; // News API:n URL
    protected $apikey = '521702070919459486138d7ad87db063'; // News API:n apikey
    protected $pagecount = 5; // Haettavien uutisten lukumäärä
    protected $language = 'fi'; // Haettavien uutisten kieli

    // Hakee uutiset, joissa on sanat korvattu
    public function news() {
        $keyword = $_REQUEST['keyword'];
        $articles = $this->getArticles($keyword);
        $this->set('articles', $articles);
    }

    // Näyttää kaikki tietokannan sanaparit
    public function view() {
        $wordsTable = TableRegistry::get('Words');
        $word_list = $wordsTable->find('all');
        $this->set('word_list', $word_list);
    }

    // Lisää uuden sanaparin
    public function add() {
        $wordsTable = TableRegistry::get('Words');
        $last_row = $wordsTable->find()->order(['id' => 'DESC'])->first();
        $words = $wordsTable->newEntity();
        $words->search_word = $_REQUEST['search_word'];
        $words->replace_word = $_REQUEST['replace_word'];
        $new_words = $wordsTable->save($words);
        if((string)$new_words['id'] > (string)$last_row['id']) $message = 'success';
        else $message = 'error';
        $this->set([
            'new_words' => $new_words,
            'message' => $message,
            '_serialize' => ['new_words', 'message']
        ]);
    }

    // Poistaa sanaparin
    public function delete() {
        $wordsTable = TableRegistry::get('Words');
        $id = $_REQUEST['id'];
        $entity = $wordsTable->get($id);
        $deleted = $wordsTable->delete($entity);
        if($deleted) $message = 'success';
        else $message = 'error';
        $this->set([
            'deleted' => $deleted,
            'message' => $message,
            '_serialize' => ['deleted', 'message']
        ]);
    }
    // Hakee uutiset
    public function getArticles($condition) {
        $http = new Client();
        $response = $http->get($this->url, [
                'q' => $condition,
                'language' => $this->language,
                'pageSize' => $this->pagecount,
                'apiKey' => $this->apikey
            ]);
        $responseJson = json_decode($response->getStringBody(), true);
        $newResponses = array();
        foreach($responseJson['articles'] as $rows){
            $title = $this->replaceWords($rows['title']);
            $description = $this->replaceWords($rows['description']);
            $article = array(
                'name' => $rows['source']['name'],
                'author' => $rows['author'],
                'title' => $title,
                'description' => $description,
                'url' => $rows['url']
            );
            $newResponses[] = $article;
        }
        return $newResponses;
    }

    // Korvaa sanat
    public function replaceWords($content) {
        $wordsTable = TableRegistry::get('Words');
        $word_list = $wordsTable->find('all');
        $replaceArray = array();
        foreach ($word_list as $row) {
            $replaceArray[$row['search_word']] = $row['replace_word'];
        }
        $field = str_replace(array_keys($replaceArray), array_values($replaceArray), $content);
        return $field;
    }
}
