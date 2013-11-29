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
        $criteria = new CDbCriteria;
        $criteria->addInCondition('type_id', $typeIds);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Allows filtering to a specific Open street maps node id
     *
     * @param  int $osmNodeId     - id of the OSM node
     *
     * @return OsmLaneRestriction - model for chaining
     */
    public function osmNode($osmNodeId = null) {
        if (is_null($osmNodeId)) {
            return $this;
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition(
            'osm_node_a_id = :osmNodeId OR osm_node_b_id = :osmNodeId'
        );
        $criteria->params = array(
            ':osmNodeId' => $osmNodeId,
        );
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Allows filtering to a set of Open street maps node ids
     *
     * @param  array $osmNodeId   - expects an array of osm node ids
     *                              @example array(array(12345,12335))
     *
     * @return OsmLaneRestriction - model for chaining
     */
    public function osmNodes(array $osmNodeIds = null) {
        if (is_null($osmNodeIds)) {
            return $this;
        }
        $criteria = new CDbCriteria;
        $criteria->addInCondition('osm_node_a_id', $osmNodeIds);
        $criteria->addInCondition('osm_node_b_id', $osmNodeIds, 'OR');
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Limits returned results to only those with valid restrictions in place
     * A lane restriction record is considered to have a restriction in place if
     * any of the following is true. a -> b is closed, b -> a is closed,
     * a -> b speed is not null or b -> a speed is not null
     *
     * @param  mixed $hasRestrictions - (string|boolean|int) whether to filter
     *                                  will filter on a 1, '1' or true value
     *
     * @return OsmLaneRestriction     - model for chaining
     */
    public function hasRestrictions($hasRestrictions = null) {

        if (is_null($hasRestrictions)
            || false === in_array($hasRestrictions, array('1','true'))
        ) {
            return $this;
        }

        $criteria = new CDbCriteria;
        $criteria->addCondition('a_to_b_is_closed = 1');
        $criteria->addCondition('b_to_a_is_closed = 1', 'OR');
        $criteria->addCondition('a_to_b_speed_limit IS NOT NULL', 'OR');
        $criteria->addCondition('b_to_a_speed_limit IS NOT NULL', 'OR');
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Filters based on given single osm path array. A path is an array with
     * 2 integer osm node id values. @example array(12345,123124)
     *
     * @param  array $osmPath     - path to restrict result set to
     *
     * @return OsmLaneRestriction - model for chaining
     */
    public function osmPath(array $osmPath = null) {
        if (is_null($osmPath)) {
            return $this;
        }
        $criteria = $this->pathCriteria($osmPath);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * (Scope)
     * Filters based on an array of osm path arrays. Will only return a result
     * if it has a node id A and a node id B that match one of the passed in
     * path nodes @example array(array(12345,12334), array(12346,12345))
     * If a record has node a id 12334 and node b id of 12345 it would match
     * Order of A and B is not important
     *
     * @param  array $osmPaths    - array of osm node id arrays
     *
     * @return OsmLaneRestriction - model for chaining
     */
    public function osmPaths(array $osmPaths = null) {
        if (is_null($osmPaths)) {
            return $this;
        }

        $criteria = new CDbCriteria;

        foreach($osmPaths as $path) {
            $subCriteria = $this->pathCriteria($path);
            $criteria->mergeWith($subCriteria, 'OR');
        }

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * Scope helper method. Returns a criteria object to match paths given
     * a single path array with a node a id and a node b id
     *
     * @param  array $osmPath - array with 2 node id values
     *                          @example array(12345,12314)
     *
     * @return CDbCriteria    - criteria object
     */
    private function pathCriteria(array $osmPath) {
        $p = new CHtmlPurifier();
        $pathA = $p->purify($osmPath[0]);
        $pathB = $p->purify($osmPath[1]);
        $criteria = new CDbCriteria;
        $criteria->addCondition(
            "osm_node_a_id = {$pathA} AND osm_node_b_id = {$pathB}"
        );
        $criteria->addCondition(
            "osm_node_b_id = {$pathA} AND osm_node_a_id = {$pathB}",
            'OR'
        );
        return $criteria;
    }

}
