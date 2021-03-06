<?php
namespace addons\RfArticle\common\models;

use Yii;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_article_adv}}".
 *
 * @property int $id 序号
 * @property string $title 标题
 * @property string $cover 图片
 * @property int $location_id 广告位ID
 * @property string $silder_text 图片描述
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property string $jump_link 跳转链接
 * @property int $jump_type 跳转方式[1:新标签; 2:当前页]
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Adv extends \common\models\common\BaseModel
{
    const LOCATION_INDEX = 1;

    /**
     * @var array
     */
    public static $localExplain = [
        self::LOCATION_INDEX => '首页',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_article_adv}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'cover', 'start_time', 'end_time'], 'required'],
            [['location_id', 'jump_type', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['cover'], 'string', 'max' => 100],
            [['silder_text', 'jump_link'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'cover' => '封面',
            'location_id' => '幻灯片位置',
            'silder_text' => '图片描述',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'jump_link' => '跳转链接',
            'jump_type' => '跳转类型',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 根据开始时间和结束时间发回当前状态
     *
     * @param int $start_time 开始时间
     * @param int $end_time 结束时间
     * @return mixed
     */
    public static function getTimeStatus($start_time, $end_time)
    {
        $time = time();
        if ($start_time > $end_time)
        {
            return "<span class='label label-danger'>有效期错误</span>";
        }
        elseif ($start_time > $time)
        {
            return "<span class='label label-default'>未开始</span>";
        }
        elseif ($start_time < $time && $end_time > $time)
        {
            return "<span class='label label-primary'>进行中</span>";
        }
        elseif ($end_time < $time)
        {
            return "<span class='label label-default'>已结束</span>";
        }

        return false;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->start_time = StringHelper::dateToInt($this->start_time);
        $this->end_time = StringHelper::dateToInt($this->end_time);

        return parent::beforeSave($insert);
    }
}
