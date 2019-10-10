<?php
namespace App\Model\Entity;
use Cake\ORM\Entity;

class Words extends Entity
{
    protected $_accessible = [
        'search_word' => true,
        'replace_word' => true
    ];
}