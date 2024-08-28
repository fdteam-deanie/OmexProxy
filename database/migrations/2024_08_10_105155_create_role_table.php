<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->integer('index')->default(0);

      /* USER PERMISSIONS START */
      $table->boolean('view_any_user')->default(0);
      $table->boolean('view_user')->default(0);
      $table->boolean('edit_user')->default(0);
      $table->boolean('create_user')->default(0);
      $table->boolean('update_user')->default(0);
      $table->boolean('restore_user')->default(0);
      $table->boolean('delete_user')->default(0);
      $table->boolean('force_delete_user')->default(0);
      $table->boolean('replicate_user')->default(0);
      $table->boolean('attach_user')->default(0);
      $table->boolean('detach_user')->default(0);
      $table->boolean('add_user')->default(0);
      $table->boolean('run_action_user')->default(0);
      /* USER PERMISSIONS END */

      /* ROLE PERMISSIONS START */
      $table->boolean('view_any_role')->default(0);
      $table->boolean('view_role')->default(0);
      $table->boolean('create_role')->default(0);
      $table->boolean('update_role')->default(0);
      $table->boolean('edit_role')->default(0);
      $table->boolean('restore_role')->default(0);
      $table->boolean('delete_role')->default(0);
      $table->boolean('force_delete_role')->default(0);
      $table->boolean('replicate_role')->default(0);
      $table->boolean('attach_role')->default(0);
      $table->boolean('detach_role')->default(0);
      $table->boolean('add_role')->default(0);
      $table->boolean('run_action_role')->default(0);
      /* ROLE PERMISSIONS END */

      /* TICKETS PERMISSIONS START */
      $table->boolean('view_any_tickets')->default(0);
      $table->boolean('view_tickets')->default(0);
      $table->boolean('create_tickets')->default(0);
      $table->boolean('update_tickets')->default(0);
      $table->boolean('edit_tickets')->default(0);
      $table->boolean('restore_tickets')->default(0);
      $table->boolean('delete_tickets')->default(0);
      $table->boolean('force_delete_tickets')->default(0);
      $table->boolean('replicate_tickets')->default(0);
      $table->boolean('attach_tickets')->default(0);
      $table->boolean('detach_tickets')->default(0);
      $table->boolean('add_tickets')->default(0);
      $table->boolean('run_action_tickets')->default(0);
      /* TICKETS PERMISSIONS END */

      /* COMPLAINTS ERMISSIONS START */
      $table->boolean('view_any_complaint')->default(0);
      $table->boolean('view_complaint')->default(0);
      $table->boolean('create_complaint')->default(0);
      $table->boolean('update_complaint')->default(0);
      $table->boolean('edit_complaint')->default(0);
      $table->boolean('restore_complaint')->default(0);
      $table->boolean('delete_complaint')->default(0);
      $table->boolean('force_delete_complaint')->default(0);
      $table->boolean('replicate_complaint')->default(0);
      $table->boolean('attach_complaint')->default(0);
      $table->boolean('detach_complaint')->default(0);
      $table->boolean('add_complaint')->default(0);
      $table->boolean('run_action_complaint')->default(0);
      /* COMPLAINTS PERMISSIONS END */

      /* PURCHASEHISTORY PERMISSIONS START */
      $table->boolean('view_any_purchase')->default(0);
      $table->boolean('view_purchase')->default(0);
      $table->boolean('create_purchase')->default(0);
      $table->boolean('update_purchase')->default(0);
      $table->boolean('edit_purchase')->default(0);
      $table->boolean('restore_purchase')->default(0);
      $table->boolean('delete_purchase')->default(0);
      $table->boolean('force_delete_purchase')->default(0);
      $table->boolean('replicate_purchase')->default(0);
      $table->boolean('attach_purchase')->default(0);
      $table->boolean('detach_purchase')->default(0);
      $table->boolean('add_purchase')->default(0);
      $table->boolean('run_action_purchase')->default(0);
      /* PURCHASEHISTORY PERMISSIONS END */

      /* TRANSACTIONS PERMISSIONS START */
      $table->boolean('view_any_transaction')->default(0);
      $table->boolean('view_transaction')->default(0);
      $table->boolean('create_transaction')->default(0);
      $table->boolean('update_transaction')->default(0);
      $table->boolean('edit_transaction')->default(0);
      $table->boolean('restore_transaction')->default(0);
      $table->boolean('delete_transaction')->default(0);
      $table->boolean('force_delete_transaction')->default(0);
      $table->boolean('replicate_transaction')->default(0);
      $table->boolean('attach_transaction')->default(0);
      $table->boolean('detach_transaction')->default(0);
      $table->boolean('add_transaction')->default(0);
      $table->boolean('run_action_transaction')->default(0);
      /* TRANSACTIONS PERMISSIONS END */

      /* PROXY PERMISSIONS START */
      $table->boolean('view_any_proxy')->default(0);
      $table->boolean('view_proxy')->default(0);
      $table->boolean('create_proxy')->default(0);
      $table->boolean('update_proxy')->default(0);
      $table->boolean('edit_proxy')->default(0);
      $table->boolean('restore_proxy')->default(0);
      $table->boolean('delete_proxy')->default(0);
      $table->boolean('force_delete_proxy')->default(0);
      $table->boolean('replicate_proxy')->default(0);
      $table->boolean('attach_proxy')->default(0);
      $table->boolean('detach_proxy')->default(0);
      $table->boolean('add_proxy')->default(0);
      $table->boolean('run_action_proxy')->default(0);
      /* PROXY PERMISSIONS END */

      /* HOSTINGMANAGEMENT PERMISSIONS START */
      $table->boolean('view_any_hostingmanagement')->default(0);
      $table->boolean('view_hostingmanagement')->default(0);
      $table->boolean('create_hostingmanagement')->default(0);
      $table->boolean('update_hostingmanagement')->default(0);
      $table->boolean('edit_hostingmanagement')->default(0);
      $table->boolean('restore_hostingmanagement')->default(0);
      $table->boolean('delete_hostingmanagement')->default(0);
      $table->boolean('force_delete_hostingmanagement')->default(0);
      $table->boolean('replicate_hostingmanagement')->default(0);
      $table->boolean('attach_hostingmanagement')->default(0);
      $table->boolean('detach_hostingmanagement')->default(0);
      $table->boolean('add_hostingmanagement')->default(0);
      $table->boolean('run_action_hostingmanagement')->default(0);
      /* HOSTINGMANAGEMENT PERMISSIONS END */

      /* PROXYRENTPERIOD PERMISSIONS START */
      $table->boolean('view_any_proxyrentperiod')->default(0);
      $table->boolean('view_proxyrentperiod')->default(0);
      $table->boolean('create_proxyrentperiod')->default(0);
      $table->boolean('update_proxyrentperiod')->default(0);
      $table->boolean('edit_proxyrentperiod')->default(0);
      $table->boolean('restore_proxyrentperiod')->default(0);
      $table->boolean('delete_proxyrentperiod')->default(0);
      $table->boolean('force_delete_proxyrentperiod')->default(0);
      $table->boolean('replicate_proxyrentperiod')->default(0);
      $table->boolean('attach_proxyrentperiod')->default(0);
      $table->boolean('detach_proxyrentperiod')->default(0);
      $table->boolean('add_proxyrentperiod')->default(0);
      $table->boolean('run_action_proxyrentperiod')->default(0);
      /* PROXYRENTPERIOD PERMISSIONS END */

      /* RATE PERMISSIONS START */
      $table->boolean('view_any_rate')->default(0);
      $table->boolean('view_rate')->default(0);
      $table->boolean('create_rate')->default(0);
      $table->boolean('update_rate')->default(0);
      $table->boolean('edit_rate')->default(0);
      $table->boolean('restore_rate')->default(0);
      $table->boolean('delete_rate')->default(0);
      $table->boolean('force_delete_rate')->default(0);
      $table->boolean('replicate_rate')->default(0);
      $table->boolean('attach_rate')->default(0);
      $table->boolean('detach_rate')->default(0);
      $table->boolean('add_rate')->default(0);
      $table->boolean('run_action_rate')->default(0);
      /* RATE PERMISSIONS END */

      /* NOLIMITINDAY PERMISSIONS START */
      $table->boolean('view_any_nolimitinday')->default(0);
      $table->boolean('view_nolimitinday')->default(0);
      $table->boolean('create_nolimitinday')->default(0);
      $table->boolean('update_nolimitinday')->default(0);
      $table->boolean('edit_nolimitinday')->default(0);
      $table->boolean('restore_nolimitinday')->default(0);
      $table->boolean('delete_nolimitinday')->default(0);
      $table->boolean('force_delete_nolimitinday')->default(0);
      $table->boolean('replicate_nolimitinday')->default(0);
      $table->boolean('attach_nolimitinday')->default(0);
      $table->boolean('detach_nolimitinday')->default(0);
      $table->boolean('add_nolimitinday')->default(0);
      $table->boolean('run_action_nolimitinday')->default(0);
      /* NOLIMITINDAY PERMISSIONS END */

      /* TRIALPERIOD PERMISSIONS START */
      $table->boolean('view_any_trialperiod')->default(0);
      $table->boolean('view_trialperiod')->default(0);
      $table->boolean('create_trialperiod')->default(0);
      $table->boolean('update_trialperiod')->default(0);
      $table->boolean('edit_trialperiod')->default(0);
      $table->boolean('restore_trialperiod')->default(0);
      $table->boolean('delete_trialperiod')->default(0);
      $table->boolean('force_delete_trialperiod')->default(0);
      $table->boolean('replicate_trialperiod')->default(0);
      $table->boolean('attach_trialperiod')->default(0);
      $table->boolean('detach_trialperiod')->default(0);
      $table->boolean('add_trialperiod')->default(0);
      $table->boolean('run_action_trialperiod')->default(0);
      /* TRIALPERIOD PERMISSIONS END */

      /* CATEGORY PERMISSIONS START */
      $table->boolean('view_any_category')->default(0);
      $table->boolean('view_category')->default(0);
      $table->boolean('create_category')->default(0);
      $table->boolean('update_category')->default(0);
      $table->boolean('edit_category')->default(0);
      $table->boolean('restore_category')->default(0);
      $table->boolean('delete_category')->default(0);
      $table->boolean('force_delete_category')->default(0);
      $table->boolean('replicate_category')->default(0);
      $table->boolean('attach_category')->default(0);
      $table->boolean('detach_category')->default(0);
      $table->boolean('add_category')->default(0);
      $table->boolean('run_action_category')->default(0);
      /* CATEGORY PERMISSIONS END */

      /* ARTICLES PERMISSIONS START */
      $table->boolean('view_any_articles')->default(0);
      $table->boolean('view_articles')->default(0);
      $table->boolean('create_articles')->default(0);
      $table->boolean('update_articles')->default(0);
      $table->boolean('edit_articles')->default(0);
      $table->boolean('restore_articles')->default(0);
      $table->boolean('delete_articles')->default(0);
      $table->boolean('force_delete_articles')->default(0);
      $table->boolean('replicate_articles')->default(0);
      $table->boolean('attach_articles')->default(0);
      $table->boolean('detach_articles')->default(0);
      $table->boolean('add_articles')->default(0);
      $table->boolean('run_action_articles')->default(0);
      /* ARTICLES PERMISSIONS END */

      $table->boolean('view_any_wallet')->default(0);
      $table->boolean('view_any_proxyhosting')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('roles');
  }
};
