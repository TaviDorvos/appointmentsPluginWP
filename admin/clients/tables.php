<?php
if (!class_exists('Link_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

//clasa pentru tabelul de clienti
class ClientsTable extends WP_List_Table {

  static function get_clients($per_page = 15, $page_number = 1) {

    global $wpdb;

    $sql = "SELECT * FROM clienti";

    if (!empty($_REQUEST['orderby'])) {
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    $sql .= " LIMIT $per_page";

    $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


    $result = $wpdb->get_results($sql, 'ARRAY_A');

    return $result;
    var_dump($result);
  }

  function no_items() {
    _e('Niciun client disponibil.', 'sp');
  }

  static function record_count() {
    global $wpdb;

    $query = "SELECT COUNT(*) FROM clienti";

    return $wpdb->get_var($query);
  }


  function get_columns() {
    $columns = array(
      'id_client' => 'ID',
      'nume' => 'Nume',
      'email' => 'Email',
      'telefon' => 'Telefon',
      'programari' => 'Vezi programări',
      'edit' => 'Editează'
    );

    return $columns;
  }

  function prepare_items() {

    $this->_column_headers = $this->get_column_info();

    $per_page     = $this->get_items_per_page('clients_per_page', 15);
    $current_page = $this->get_pagenum();
    $total_items  = self::record_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page'    => $per_page
    ]);

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items           = self::get_clients($per_page, $current_page);
  }

  function column_default($item, $column_name) {
    switch ($column_name) {
      case 'id_client':
      case 'nume':
      case 'email':
      case 'telefon':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'id_client' => array('id_client', false),
      'nume' => array('nume', false),
    );

    return $sortable_columns;
  }

  function column_programari($item) {
    return "<a href='".admin_url('admin.php?page=clients_programari&id_client='.$item['id_client'])."'>Vezi programări</a>";
  }

  function column_edit($item) {
    return "<a href='".admin_url('admin.php?page=clients_edit&id_client='.$item['id_client'])."'>Editează</a>";
    
  }
}

//Clasa pentru tabelul de programari******
class AppointmentsTable extends WP_List_Table {

  static function get_appointments($per_page = 15, $page_number = 1) {

      global $wpdb;
      $id_client = $_GET['id_client'];

      $sql = "SELECT * FROM programari WHERE id_client='$id_client'";

      if (!empty($_REQUEST['orderby'])) {
          $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
          $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
      }

      $sql .= " LIMIT $per_page";

      $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


      $result = $wpdb->get_results($sql, 'ARRAY_A');

      return $result;
      var_dump($result);
  }

  function no_items() {
      _e('Nici o programare disponibilă.', 'sp');
  }

  static function record_count() {
      global $wpdb;

      $query = "SELECT COUNT(*) FROM programari";

      return $wpdb->get_var($query);
  }


  function get_columns() {
      $columns = array(
          'id_prog' => 'ID Programare',
          'status' => 'Status',
          'date_selected' => 'Data',
          'hour_start' => 'Ora de început',
          'hour_final' => 'Ora de sfârșit',
          'subject' => 'Subiect',
          'message' => 'Mesaj'
      );

      return $columns;
  }

  function prepare_items() {

      $this->_column_headers = $this->get_column_info();

      $per_page     = $this->get_items_per_page('appointments_per_page', 15);
      $current_page = $this->get_pagenum();
      $total_items  = self::record_count();

      $this->set_pagination_args([
          'total_items' => $total_items,
          'per_page'    => $per_page
      ]);

      $columns  = $this->get_columns();
      $hidden   = array();
      $sortable = $this->get_sortable_columns();

      $this->_column_headers = array($columns, $hidden, $sortable);
      $this->items           = self::get_appointments($per_page, $current_page);
  }

  function column_default($item, $column_name) {
      switch ($column_name) {
        case 'id_prog':
        case 'status':
        case 'date_selected':
        case 'hour_start':
        case 'hour_final':
        case 'subject':
        case 'message':
          return $item[$column_name];
        default:
          return print_r($item, true);
      }
  }

  function get_sortable_columns() {
      $sortable_columns = array(
          'id_prog' => array('id_prog', false),
          'status' => array('status', false),
          'date_selected' => array('date_selected', false),
          'hour_start' => array('hour_start', false),
          'hour_final' => array('hour_final', false),
      );

      return $sortable_columns;
  }
}


