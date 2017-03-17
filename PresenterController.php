<?php
/**
 * User: shoal
 * Date: 2017/3/17
 * Time: 11:31
 */

namespace chenqd\presenter;

use yii\console\Controller;
use Yii;
use yii\helpers\Console;

class PresenterController extends Controller
{
    public $force = false;
    public $base_path;
    public $namespace;

    public function beforeAction($action) {

        if (is_null($this->base_path)) {
            $this->initPath();
        }
        return parent::beforeAction($action);
    }

    /**
     * auto set path if never set base_path
     */
    public function initPath()
    {
        if (Yii::getAlias('@common')) {
            //advanced template
            $this->base_path = '@common/models/presenters';
            $this->namespace = 'common\models\presenters';
        } else {
            //basic template
            $this->base_path = '@app/models/presenters';
            $this->namespace = 'app\models\presenters';
        }
    }
    
    public function actionCreate($name)
    {
        $this->create($name);
    }

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            $actionID === 'create' ? ['force'] : [] // global for all actions
        );
    }

    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), [
            'f' => 'force',
        ]);
    }

    public function create($name)
    {
        if (!ends_with($name, 'Presenter')) {
            $name = $name . 'Presenter';
        }

        $filePath = Yii::getAlias(rtrim($this->base_path, '/')."/{$name}.php");

        if (file_exists($filePath) && !$this->force) {
            $this->stdout("Presenter `$name` has existed\n", Console::FG_YELLOW);
            return;
        }

        $stub = file_get_contents(__DIR__.'/presenter.stub');
        $content = strtr($stub,[
            '{{namespace}}' => $this->namespace,
            '{{name}}' => $name.'Presenter',
        ]);
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath));
        }

        file_put_contents($filePath, $content);
        $this->stdout("Presenter `$name` generate success\n", Console::FG_GREEN);
    }

}