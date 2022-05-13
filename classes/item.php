<?php
  class Items {
    public function createItem($filename, $name, $price, $ingredients, $categoryId, $vatStatus, $tmp) {
      global $db, $fun, $prc, $ing;
      
      // saving item with its price & ingredients
      $db->create(TBL_ITEM, "name='$name', image='$filename', vat_status='$vatStatus', category_id='$categoryId', location_id='" . LOCATION . "', user_id='" . ID . "', date='" . CURRENT_DATE . "', tm='" . CURRENT_TIME . "', status = 0");

      // move_uploaded_file($tmp, '/vendor/images/' . $filename);

      $itemId = $this->getItemIdByName($name);
      $prc->createItemPrice($itemId, $categoryId, $price);
      !empty($ingredients) && $ing->createItemIngredients($ingredients, $itemId, $categoryId);

      $fun->jsonResponse(true, "Entry saved successfully", "200");
    }

    public function itemRows($fields="*", $query="", $column="") {
      global $db;
      
      $queries = !empty($query) ? "$query AND" : "";
      return $db->fetch(TBL_ITEM, $fields, "$queries status=0", $column);
    }

    public function getItem($fields="*", $conditions="", $col="") {
      global $db;
      
      $con = !empty($conditions) ? "AND $conditions" : "";
      return $this->itemRows($fields, "location_id='" . LOCATION . "' $con", $col);
    }

    public function getItemIdByName($name) {
      return $this->getItem("id", "name='$name'", "id");
    }

    public function getItemData() {
      global $prc, $ing;

      $data = [];
      $items = $this->getItem();

      foreach($items as $item) {
        $itemId=$item['id'];
        $price = $prc->getItemPrice("price", "item_id='$itemId'", "price");
        $ingredients = $ing->getItemIngredients("id, name, item_id, status", "item_id='$itemId'");
        $data[] = array_merge($item, ["price" => $price, "ingredients" => $ingredients]);
      }

      return $data;
    }
  }