<?php

namespace sqlNested;

class Recurser 
{

    private static function recurseRemains( $mapping , &$ret= [] ) 
    {
      foreach($mapping as $key => $mappingOrValue ) {
        if(is_array($mappingOrValue)) {
          $index = preg_replace('/^(.*)s$/','$1_id',$key);
          $ret[] = $index;
          self::recurseRemains( $mappingOrValue , $ret);
        } else {
          $ret[] = $mappingOrValue;
        }
      }
    }
    

    private function fieldsForLevelAndRemains( $mapping, $level='root', &$ret = [], &$remainings=[] )
    {
      
        foreach( $mapping as $key => $valueOrMapping ) {
          if(!is_array($valueOrMapping)) {
            $ret[] = $valueOrMapping;
          } else {
            $firstIndex = preg_replace('/^(.*)s$/','$1_id',$key);
            $remainings[$key] = [$firstIndex];
          

            self::recurseRemains($valueOrMapping, $remainings[$key]);
          }
        }
        return;
    }

    private static function splitsParentAndRemains($row, $mapping, $level ) 
    {
      
      $fields = [];
      $remainings = [];
      $parentRow = [];
      $remainingRows = [];

      self::fieldsForLevelAndRemains( $mapping, $level, $fields, $remainings );

      foreach($row as $key => $value) {
        if(false !== array_search($key, $fields)) {
          $parentRow[$key] = $value;
        } else {
          foreach($remainings as $subLevel => $remainingRow) {
            if(false !== array_search($key, $remainingRow)) {
              if( !is_array( $value) && is_array( @unserialize($value) ) ) {
                $value = unserialize($value);
              }
              $remainingRows[$subLevel][$key] = $value;
            }
          }
        }
      }

      
      foreach($remainingRows as $key => $row) {
        $isEmpty = true;
        foreach($row as $value) {
          if(isset($value)) {
            $isEmpty = false;
          }
        }
        if($isEmpty) {
          unset($remainingRows[$key]);
        }
      }
      return ['parentRow' => $parentRow, 'remainingRows' => $remainingRows];
    }

    private static function rankIndexValue( $results , $indexValue) {
      foreach($results as $index => $row ) {
        if($row['id'] == $indexValue) {
          return $index;
        }
      }
      return false;
    }

    public static function recurse( $results, $mapping, &$res=[], $currentLevel = 'root' , $parentIndex = 'id' ) {
      
      $ret= [];
      
      foreach( $results as $row ) {
        
        $parentAndRemains = self::splitsParentAndRemains($row, $mapping, $currentLevel );

        $parentRow = $parentAndRemains['parentRow'];
        $remainingRows = $parentAndRemains['remainingRows'];

        if( false !== $i = self::rankIndexValue($ret, $row[$parentIndex]) ) {
          foreach($remainingRows as $key => $remaingRow ) {
            $ret[$i][$key][] = $remaingRow;
          }
        } else {
          $parentRow['id'] = $row[$parentIndex];
          if($parentIndex != 'id') {
            unset($parentRow[$parentIndex]);
          }
          $i = array_push($ret,$parentRow);
          $j = array_push($res,$parentRow);
          foreach($remainingRows as $key => $remaingRow ) {
            $ret[$i-1][$key] = [];
            $res[$i-1][$key] = [];
            $ret[$i-1][$key][] = $remaingRow;
          }
        }
      }
      

      foreach( $mapping as $key => $valueOrMapping) {
        if(is_array($valueOrMapping)) {
          $parentIndex = preg_replace('/^(.*)s$/','$1_id',$key);
          foreach($ret as $i => $row ) {
            if(isset($ret[$i][$key])) {
              self::recurse($ret[$i][$key], $valueOrMapping, $res[$i][$key], $key , $parentIndex);
              
            }
          }
        }
      }
    }  
}