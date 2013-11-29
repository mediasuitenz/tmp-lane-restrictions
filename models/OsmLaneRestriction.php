<?php

/**
 * This is the model class for table "osm_lane_restriction".
 *
 * The followings are the available columns in table 'osm_lane_restriction':
 * @property integer $id
 * @property string $type
 * @property integer $type_id
 * @property integer $osm_node_a_id
 * @property integer $osm_node_a_version_id
 * @property integer $osm_node_b_id
 * @property integer $osm_node_b_version_id
 * @property integer $a_to_b_is_closed
 * @property integer $b_to_a_is_closed
 * @property integer $a_to_b_speed_limit
 * @property integer $b_to_a_speed_limit
 */
class OsmLaneRestriction extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'osm_lane_restriction';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('type, type_id, osm_node_a_id, osm_node_a_version_id,
                osm_node_b_id, osm_node_b_version_id',
                'required'
            ),
            array('type_id, osm_node_a_id, osm_node_a_version_id,
                osm_node_b_id, osm_node_b_version_id, a_to_b_is_closed,
                b_to_a_is_closed, a_to_b_speed_limit, b_to_a_speed_limit',
                'numerical',
                'integerOnly'=>true
            ),
            array('type', 'length', 'max'=>15),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'                    => 'ID',
            'type'                  => 'Type',
            'type_id'               => 'Type',
            'osm_node_a_id'         => 'Osm Node A',
            'osm_node_a_version_id' => 'Osm Node A Version',
            'osm_node_b_id'         => 'Osm Node B',
            'osm_node_b_version_id' => 'Osm Node B Version',
            'a_to_b_is_closed'      => 'A To B Is Closed',
            'b_to_a_is_closed'      => 'B To A Is Closed',
            'a_to_b_speed_limit'    => 'A To B Speed Limit',
            'b_to_a_speed_limit'    => 'B To A Speed Limit',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method
     * in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OsmLaneRestriction the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * (Scope)
     * Filters requested OsmLaneRestriction models by type column
     *
     * @param  string $type  - refers to linking table @example RoadClosure
     *
     * @return CActiveRecord - Returns this model instance
     */
    public function type($type) {
        if (is_null($type)) {
            return $this;
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition('type = :type');
        $criteria->params = array(':type' => $type);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Filters requested OsmLaneRestriction models by type_id column
     *
     * @param  string $typeId  - refers to type tables id
     *
     * @return CActiveRecord - Returns this model instance
     */
    public function typeId($typeId = null) {
        if (is_null($typeId)) {
            return $this;
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition('type_id = :typeId');
        $criteria->params = array(':typeId' => $typeId);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Allows bulk filtering of Lane Restrictions by an array of typeids
     *
     * @param  array $typeIds - array of ids @example array(1234,1235,1246)
     *
     * @return CActiveRecord  - Returns this model instance
     */
    public function typeIds(array $typeIds = null) {
        if (is_null($typeIds)) {
            return $this;
        }
        // var_dump($typeIds); die;
        $criteria = new CDbCriteria;
        $criteria->addInCondition('type_id', $typeIds);
        // $criteria->addInCondition('type_id', array(123, 124));
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }
}
