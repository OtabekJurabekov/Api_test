<?php

namespace app\models;

use yii\base\Model;

class CarForm extends Model
{
    public $model;
    public $brand;
    public $description;
    public $color;
    public $year;
    public $price;

    public function rules()
    {
        return [
            [['model', 'brand', 'description', 'color', 'year', 'price'], 'required'],
            [['description'], 'string'],
            [['price'], 'integer'],
            [['model', 'brand', 'color'], 'string', 'max' => 255],
            [['year'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }
}
