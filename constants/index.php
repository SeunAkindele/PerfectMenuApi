<?php
  // TABLES
  define("TBL_LOCATION", "location");
  define("TBL_USER", "users");
  define("TBL_ITEM", "items");
  define("TBL_ITEM_CATEGORY", "category");
  define("TBL_PRICE", "price");
  define("TBL_INGREDIENT", "ingredients");
  define("TBL_CART", "cart");
  define("TBL_ORDER", "orders");
  define("TBL_TOKEN", "token");
  define("TBL_TXN", "transaction");
  define("TBL_RATE", "ratings");

  // DATES
  define("CURRENT_YEAR", date("Y"));
  define("CURRENT_MONTH", date("m"));
  define("CURRENT_DAY", date("d"));
  define("CURRENT_MONTH_IN_WORD", date("F"));
  define("CURRENT_DATE", date("Y-m-d"));
  define("CURRENT_TIME", date("H:i:s"));
  define("CURRENT_UPDATE", date("Y-m-d H:i:s"));
  define("PREVIOUS_DATE", date('Y-m-d', strtotime(' -1 day')));
  define("SECOND_PREVIOUS_DATE", date('Y-m-d', strtotime(' -2 day')));
  define("THIRD_PREVIOUS_DATE", date('Y-m-d', strtotime(' -3 day')));
  define("FOURTH_PREVIOUS_DATE", date('Y-m-d', strtotime(' -4 day')));
  define("FIFTH_PREVIOUS_DATE", date('Y-m-d', strtotime(' -5 day')));
  define("SIXTH_PREVIOUS_DATE", date('Y-m-d', strtotime(' -6 day')));