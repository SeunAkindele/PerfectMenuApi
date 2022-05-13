<?php
  class Ingredients {
    public function createItemIngredients($ingredients, $itemId, $categoryId) {
      global $db;
     
      foreach($ingredients as $ingredient) {
        $name = $db->escape($ingredient);

        $db->create(TBL_INGREDIENT, "name='$name', item_id='$itemId', category_id='$categoryId', location_id='" . LOCATION . "', user_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");
      }
      
    }

    public function itemIngredientsRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_INGREDIENT, $fields, "$queries status=0", $column);
    }

    public function getItemIngredients($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->itemIngredientsRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }
  }