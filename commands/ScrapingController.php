<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Site;
use yii\console\Controller;
use app\models\Article;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ScrapingController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $model = Article::findOne(1);
        $model->site_id = 1;
        $model->source_url = "bbbbbbbb";
        $model->save();
        $model->refresh();
//        $model->touch('updated');
//        $model->touch('created');
        var_dump($model->getAttributes());
        echo $message . "\n";
    }
}
