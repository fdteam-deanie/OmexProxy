<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('roles')->insert([
      [
        'name' => 'Guest',
        'index' => 1,
        'view_any_user' => 0,
        'view_user' => 0,
        'edit_user' => 0,
        'create_user' => 0,
        'update_user' => 0,
        'restore_user' => 0,
        'delete_user' => 0,
        'force_delete_user' => 0,
        'replicate_user' => 0,
        'attach_user' => 0,
        'detach_user' => 0,
        'add_user' => 0,
        'run_action_user' => 0,
        'view_any_role' => 0,
        'view_role' => 0,
        'create_role' => 0,
        'update_role' => 0,
        'edit_role' => 0,
        'restore_role' => 0,
        'delete_role' => 0,
        'force_delete_role' => 0,
        'replicate_role' => 0,
        'attach_role' => 0,
        'detach_role' => 0,
        'add_role' => 0,
        'run_action_role' => 0,
        'view_any_tickets' => 1,
        'view_tickets' => 1,
        'create_tickets' => 0,
        'update_tickets' => 0,
        'edit_tickets' => 0,
        'restore_tickets' => 0,
        'delete_tickets' => 0,
        'force_delete_tickets' => 0,
        'replicate_tickets' => 0,
        'attach_tickets' => 0,
        'detach_tickets' => 0,
        'add_tickets' => 0,
        'run_action_tickets' => 0,
        'view_any_complaint' => 0,
        'view_complaint' => 0,
        'create_complaint' => 0,
        'update_complaint' => 0,
        'edit_complaint' => 0,
        'restore_complaint' => 0,
        'delete_complaint' => 0,
        'force_delete_complaint' => 0,
        'replicate_complaint' => 0,
        'attach_complaint' => 0,
        'detach_complaint' => 0,
        'add_complaint' => 0,
        'run_action_complaint' => 0,
        'view_any_purchase' => 0,
        'view_purchase' => 0,
        'create_purchase' => 0,
        'update_purchase' => 0,
        'edit_purchase' => 0,
        'restore_purchase' => 0,
        'delete_purchase' => 0,
        'force_delete_purchase' => 0,
        'replicate_purchase' => 0,
        'attach_purchase' => 0,
        'detach_purchase' => 0,
        'add_purchase' => 0,
        'run_action_purchase' => 0,
        'view_any_transaction' => 0,
        'view_transaction' => 0,
        'create_transaction' => 0,
        'update_transaction' => 0,
        'edit_transaction' => 0,
        'restore_transaction' => 0,
        'delete_transaction' => 0,
        'force_delete_transaction' => 0,
        'replicate_transaction' => 0,
        'attach_transaction' => 0,
        'detach_transaction' => 0,
        'add_transaction' => 0,
        'run_action_transaction' => 0,
        'view_any_proxy' => 0,
        'view_proxy' => 0,
        'create_proxy' => 0,
        'update_proxy' => 0,
        'edit_proxy' => 0,
        'restore_proxy' => 0,
        'delete_proxy' => 0,
        'force_delete_proxy' => 0,
        'replicate_proxy' => 0,
        'attach_proxy' => 0,
        'detach_proxy' => 0,
        'add_proxy' => 0,
        'run_action_proxy' => 0,
        'view_any_hostingmanagement' => 0,
        'view_hostingmanagement' => 0,
        'create_hostingmanagement' => 0,
        'update_hostingmanagement' => 0,
        'edit_hostingmanagement' => 0,
        'restore_hostingmanagement' => 0,
        'delete_hostingmanagement' => 0,
        'force_delete_hostingmanagement' => 0,
        'replicate_hostingmanagement' => 0,
        'attach_hostingmanagement' => 0,
        'detach_hostingmanagement' => 0,
        'add_hostingmanagement' => 0,
        'run_action_hostingmanagement' => 0,
        'view_any_proxyrentperiod' => 0,
        'view_proxyrentperiod' => 0,
        'create_proxyrentperiod' => 0,
        'update_proxyrentperiod' => 0,
        'edit_proxyrentperiod' => 0,
        'restore_proxyrentperiod' => 0,
        'delete_proxyrentperiod' => 0,
        'force_delete_proxyrentperiod' => 0,
        'replicate_proxyrentperiod' => 0,
        'attach_proxyrentperiod' => 0,
        'detach_proxyrentperiod' => 0,
        'add_proxyrentperiod' => 0,
        'run_action_proxyrentperiod' => 0,
        'view_any_rate' => 0,
        'view_rate' => 0,
        'create_rate' => 0,
        'update_rate' => 0,
        'edit_rate' => 0,
        'restore_rate' => 0,
        'delete_rate' => 0,
        'force_delete_rate' => 0,
        'replicate_rate' => 0,
        'attach_rate' => 0,
        'detach_rate' => 0,
        'add_rate' => 0,
        'run_action_rate' => 0,
        'view_any_nolimitinday' => 0,
        'view_nolimitinday' => 0,
        'create_nolimitinday' => 0,
        'update_nolimitinday' => 0,
        'edit_nolimitinday' => 0,
        'restore_nolimitinday' => 0,
        'delete_nolimitinday' => 0,
        'force_delete_nolimitinday' => 0,
        'replicate_nolimitinday' => 0,
        'attach_nolimitinday' => 0,
        'detach_nolimitinday' => 0,
        'add_nolimitinday' => 0,
        'run_action_nolimitinday' => 0,
        'view_any_trialperiod' => 0,
        'view_trialperiod' => 0,
        'create_trialperiod' => 0,
        'update_trialperiod' => 0,
        'edit_trialperiod' => 0,
        'restore_trialperiod' => 0,
        'delete_trialperiod' => 0,
        'force_delete_trialperiod' => 0,
        'replicate_trialperiod' => 0,
        'attach_trialperiod' => 0,
        'detach_trialperiod' => 0,
        'add_trialperiod' => 0,
        'run_action_trialperiod' => 0,
        'view_any_category' => 0,
        'view_category' => 0,
        'create_category' => 0,
        'update_category' => 0,
        'edit_category' => 0,
        'restore_category' => 0,
        'delete_category' => 0,
        'force_delete_category' => 0,
        'replicate_category' => 0,
        'attach_category' => 0,
        'detach_category' => 0,
        'add_category' => 0,
        'run_action_category' => 0,
        'view_any_articles' => 0,
        'view_articles' => 0,
        'create_articles' => 0,
        'update_articles' => 0,
        'edit_articles' => 0,
        'restore_articles' => 0,
        'delete_articles' => 0,
        'force_delete_articles' => 0,
        'replicate_articles' => 0,
        'attach_articles' => 0,
        'detach_articles' => 0,
        'add_articles' => 0,
        'run_action_articles' => 0,
        'view_any_wallet' => 0,
        'view_any_proxyhosting' => 0,
      ],

      [
        'name' => 'SuperAdmin',
        'index' => 999,
        'view_any_user' => 1,
        'view_user' => 1,
        'edit_user' => 1,
        'create_user' => 1,
        'update_user' => 1,
        'restore_user' => 1,
        'delete_user' => 1,
        'force_delete_user' => 1,
        'replicate_user' => 1,
        'attach_user' => 1,
        'detach_user' => 1,
        'add_user' => 1,
        'run_action_user' => 1,
        'view_any_role' => 1,
        'view_role' => 1,
        'create_role' => 1,
        'update_role' => 1,
        'edit_role' => 1,
        'restore_role' => 1,
        'delete_role' => 1,
        'force_delete_role' => 1,
        'replicate_role' => 1,
        'attach_role' => 1,
        'detach_role' => 1,
        'add_role' => 1,
        'run_action_role' => 1,
        'view_any_tickets' => 1,
        'view_tickets' => 1,
        'create_tickets' => 1,
        'update_tickets' => 1,
        'edit_tickets' => 1,
        'restore_tickets' => 1,
        'delete_tickets' => 1,
        'force_delete_tickets' => 1,
        'replicate_tickets' => 1,
        'attach_tickets' => 1,
        'detach_tickets' => 1,
        'add_tickets' => 1,
        'run_action_tickets' => 1,
        'view_any_complaint' => 1,
        'view_complaint' => 1,
        'create_complaint' => 1,
        'update_complaint' => 1,
        'edit_complaint' => 1,
        'restore_complaint' => 1,
        'delete_complaint' => 1,
        'force_delete_complaint' => 1,
        'replicate_complaint' => 1,
        'attach_complaint' => 1,
        'detach_complaint' => 1,
        'add_complaint' => 1,
        'run_action_complaint' => 1,
        'view_any_purchase' => 1,
        'view_purchase' => 1,
        'create_purchase' => 1,
        'update_purchase' => 1,
        'edit_purchase' => 1,
        'restore_purchase' => 1,
        'delete_purchase' => 1,
        'force_delete_purchase' => 1,
        'replicate_purchase' => 1,
        'attach_purchase' => 1,
        'detach_purchase' => 1,
        'add_purchase' => 1,
        'run_action_purchase' => 1,
        'view_any_transaction' => 1,
        'view_transaction' => 1,
        'create_transaction' => 1,
        'update_transaction' => 1,
        'edit_transaction' => 1,
        'restore_transaction' => 1,
        'delete_transaction' => 1,
        'force_delete_transaction' => 1,
        'replicate_transaction' => 1,
        'attach_transaction' => 1,
        'detach_transaction' => 1,
        'add_transaction' => 1,
        'run_action_transaction' => 1,
        'view_any_proxy' => 1,
        'view_proxy' => 1,
        'create_proxy' => 1,
        'update_proxy' => 1,
        'edit_proxy' => 1,
        'restore_proxy' => 1,
        'delete_proxy' => 1,
        'force_delete_proxy' => 1,
        'replicate_proxy' => 1,
        'attach_proxy' => 1,
        'detach_proxy' => 1,
        'add_proxy' => 1,
        'run_action_proxy' => 1,
        'view_any_hostingmanagement' => 1,
        'view_hostingmanagement' => 1,
        'create_hostingmanagement' => 1,
        'update_hostingmanagement' => 1,
        'edit_hostingmanagement' => 1,
        'restore_hostingmanagement' => 1,
        'delete_hostingmanagement' => 1,
        'force_delete_hostingmanagement' => 1,
        'replicate_hostingmanagement' => 1,
        'attach_hostingmanagement' => 1,
        'detach_hostingmanagement' => 1,
        'add_hostingmanagement' => 1,
        'run_action_hostingmanagement' => 1,
        'view_any_proxyrentperiod' => 1,
        'view_proxyrentperiod' => 1,
        'create_proxyrentperiod' => 1,
        'update_proxyrentperiod' => 1,
        'edit_proxyrentperiod' => 1,
        'restore_proxyrentperiod' => 1,
        'delete_proxyrentperiod' => 1,
        'force_delete_proxyrentperiod' => 1,
        'replicate_proxyrentperiod' => 1,
        'attach_proxyrentperiod' => 1,
        'detach_proxyrentperiod' => 1,
        'add_proxyrentperiod' => 1,
        'run_action_proxyrentperiod' => 1,
        'view_any_rate' => 1,
        'view_rate' => 1,
        'create_rate' => 1,
        'update_rate' => 1,
        'edit_rate' => 1,
        'restore_rate' => 1,
        'delete_rate' => 1,
        'force_delete_rate' => 1,
        'replicate_rate' => 1,
        'attach_rate' => 1,
        'detach_rate' => 1,
        'add_rate' => 1,
        'run_action_rate' => 1,
        'view_any_nolimitinday' => 1,
        'view_nolimitinday' => 1,
        'create_nolimitinday' => 1,
        'update_nolimitinday' => 1,
        'edit_nolimitinday' => 1,
        'restore_nolimitinday' => 1,
        'delete_nolimitinday' => 1,
        'force_delete_nolimitinday' => 1,
        'replicate_nolimitinday' => 1,
        'attach_nolimitinday' => 1,
        'detach_nolimitinday' => 1,
        'add_nolimitinday' => 1,
        'run_action_nolimitinday' => 1,
        'view_any_trialperiod' => 1,
        'view_trialperiod' => 1,
        'create_trialperiod' => 1,
        'update_trialperiod' => 1,
        'edit_trialperiod' => 1,
        'restore_trialperiod' => 1,
        'delete_trialperiod' => 1,
        'force_delete_trialperiod' => 1,
        'replicate_trialperiod' => 1,
        'attach_trialperiod' => 1,
        'detach_trialperiod' => 1,
        'add_trialperiod' => 1,
        'run_action_trialperiod' => 1,
        'view_any_category' => 1,
        'view_category' => 1,
        'create_category' => 1,
        'update_category' => 1,
        'edit_category' => 1,
        'restore_category' => 1,
        'delete_category' => 1,
        'force_delete_category' => 1,
        'replicate_category' => 1,
        'attach_category' => 1,
        'detach_category' => 1,
        'add_category' => 1,
        'run_action_category' => 1,
        'view_any_articles' => 1,
        'view_articles' => 1,
        'create_articles' => 1,
        'update_articles' => 1,
        'edit_articles' => 1,
        'restore_articles' => 1,
        'delete_articles' => 1,
        'force_delete_articles' => 1,
        'replicate_articles' => 1,
        'attach_articles' => 1,
        'detach_articles' => 1,
        'add_articles' => 1,
        'run_action_articles' => 1,
        'view_any_wallet' => 1,
        'view_any_proxyhosting' => 1,
      ],
    ]);
  }
}
