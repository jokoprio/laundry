<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Subscription Packages
        DB::table('subscription_packages')->insert([
            [
                'id' => 'a0d4ffd8-e458-4943-af55-630b36e3247b',
                'name' => 'Trial',
                'price' => 0.00,
                'duration_days' => 14,
                'max_users' => null,
                'max_devices' => null,
                'features' => json_encode(['basic_pos']),
                'created_at' => '2026-01-13 22:00:59',
                'updated_at' => '2026-01-13 22:00:59',
            ],
        ]);

        // 2. Tenants
        DB::table('tenants')->insert([
            [
                'id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Joko Loundry',
                'logo' => null,
                'receipt_footer' => null,
                'owner_name' => 'Joko Purwanto',
                'email' => 'jokocyberlink@gmail.com',
                'phone' => '081282165618',
                'address' => null,
                'status' => 'active',
                'subscription_package_id' => 'a0d4ffd8-e458-4943-af55-630b36e3247b',
                'subscription_expires_at' => '2026-01-27 22:00:59',
                'settings' => json_encode(['currency' => 'IDR', 'timezone' => 'Asia/Jakarta']),
                'created_at' => '2026-01-13 22:00:59',
                'updated_at' => '2026-01-13 22:00:59',
                'latitude' => null,
                'longitude' => null,
            ],
        ]);

        // 3. Users
        DB::table('users')->insert([
            [
                'id' => 'a0c5500c-d3f4-4cd1-8503-fb6e77d6c9c9',
                'tenant_id' => null,
                'branch_id' => null,
                'name' => 'Super Admin',
                'email' => 'admin@gmail.com',
                'role' => 'super_admin',
                'permissions' => null,
                'is_active' => 1,
                'email_verified_at' => null,
                'password' => '$2y$10$L1akPhf5jmXDIstbev7u6eDJOgU0PHsdHVfX.7Y9/Pm8UVqzC6RSK',
                'remember_token' => null,
                'created_at' => '2026-01-06 02:52:00',
                'updated_at' => '2026-01-06 02:52:00',
            ],
            [
                'id' => 'a0d4ffd9-6365-4138-be50-e48b8c4c255c',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'name' => 'Joko Purwanto',
                'email' => 'jokocyberlink@gmail.com',
                'role' => 'owner',
                'permissions' => null,
                'is_active' => 1,
                'email_verified_at' => null,
                'password' => '$2y$10$DqG1VTKoIMut9kcS7Dk2c.abrhKwc3/x1EN1LkmqEDWip1YF/bQHa',
                'remember_token' => null,
                'created_at' => '2026-01-13 22:00:59',
                'updated_at' => '2026-01-13 22:00:59',
            ],
        ]);

        // 4. Membership Levels
        DB::table('membership_levels')->insert([
            [
                'id' => 'a0d53496-8730-42ae-a041-e8680e1f7f6e',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Medium',
                'discount_percent' => 5,
                'min_points' => 4,
                'description' => null,
                'created_at' => '2026-01-14 00:28:27',
                'updated_at' => '2026-01-14 00:28:40',
            ],
            [
                'id' => 'a0d534e2-3449-40fe-bf82-ca2858157b59',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Gold',
                'discount_percent' => 10,
                'min_points' => 10,
                'description' => null,
                'created_at' => '2026-01-14 00:29:17',
                'updated_at' => '2026-01-14 00:29:17',
            ],
        ]);

        // 5. Customers
        $customers = [
            ['id' => 'a0d53510-5a81-49e2-aa4b-a3cb9184d292', 'name' => 'Pelanggan 1', 'phone' => null],
            ['id' => 'a0d53518-28d6-4abf-a877-c615be9cbec6', 'name' => 'Pelanggan 2', 'phone' => '081282165618'],
            ['id' => 'a0d53522-26d7-4068-a34b-a6e94e23c48b', 'name' => 'Pelanggan 3', 'phone' => null],
            ['id' => 'a0d53529-5fab-42c7-89bd-53cc06035361', 'name' => 'Pelanggan 4', 'phone' => null],
            ['id' => 'a0d53530-427f-4695-b3c4-f1ff73d07818', 'name' => 'Pelanggan 5', 'phone' => null],
            ['id' => 'a0d53537-4a1c-471d-9c1a-6cfc9c04a841', 'name' => 'Pelanggan 6', 'phone' => null],
            ['id' => 'a0d53540-306f-4e63-a2bc-1f338b415b7a', 'name' => 'Pelanggan 7', 'phone' => null],
            ['id' => 'a0d53547-2c39-4b02-95d5-c8043a6f4fa2', 'name' => 'Pelanggan 8', 'phone' => null],
            ['id' => 'a0d5354e-7d8c-41a3-be8d-780e8e2f7e3a', 'name' => 'Pelanggan 9', 'phone' => null],
            ['id' => 'a0d53557-5dd3-4975-b03c-f8d3adb6590d', 'name' => 'Pelanggan 10', 'phone' => null],
            ['id' => 'a0d53561-b9b5-44ac-854a-785fc9b6bb77', 'name' => 'Pelanggan 11', 'phone' => null],
            ['id' => 'a0d5356a-f069-4dbb-b934-b5bb46bfebdb', 'name' => 'Pelanggan 12', 'phone' => null],
            ['id' => 'a0d53571-ee2c-4fdd-84ea-fa70c24456e7', 'name' => 'Pelanggan 13', 'phone' => null],
            ['id' => 'a0d53578-9831-482b-bf80-a3a028bdbefd', 'name' => 'Pelanggan 14', 'phone' => null],
            ['id' => 'a0d5357f-395c-4037-8819-25bf905f361e', 'name' => 'Pelanggan 15', 'phone' => null],
            ['id' => 'a0d53586-3318-497f-b2e3-9a94332f4921', 'name' => 'Pelanggan 16', 'phone' => null],
            ['id' => 'a0d53586-68b2-40bb-a266-d33ad0bf18f5', 'name' => 'Pelanggan 17', 'phone' => null],
            ['id' => 'a0d53590-f452-4abc-b4b8-a37aacac0496', 'name' => 'Pelanggan 18', 'phone' => null],
            ['id' => 'a0d53597-c233-4e8f-b8d3-315f4cba499a', 'name' => 'Pelanggan 19', 'phone' => null],
            ['id' => 'a0d5359f-3272-4f95-a0c7-bad0ee4dd175', 'name' => 'Pelanggan 20', 'phone' => null],
            ['id' => 'a0d535a8-3cbe-4cdc-af90-3c9c0ae3126f', 'name' => 'Pelanggan 21', 'phone' => null],
            ['id' => 'a0d535b0-c9f9-4f8f-aec5-a9a28b12d52f', 'name' => 'Pelanggan 22', 'phone' => null],
            ['id' => 'a0d535b7-4ef0-4ab7-bfb6-89c7d8abc7ec', 'name' => 'Pelanggan 23', 'phone' => null],
        ];

        foreach ($customers as $c) {
            DB::table('customers')->updateOrInsert(
                ['id' => $c['id']],
                [
                    'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                    'branch_id' => null,
                    'membership_level_id' => null,
                    'name' => $c['name'],
                    'phone' => $c['phone'],
                    'address' => null,
                    'points' => 0,
                    'balance' => 0.00,
                    'latitude' => null,
                    'longitude' => null,
                    'updated_at' => now(),
                ]
            );
        }

        // 6. Employees
        DB::table('employees')->insert([
            [
                'id' => 'a0d536c3-71a7-45a9-852e-f70dcbd58d7d',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'user_id' => null,
                'name' => 'Karyawan 1',
                'position' => 'General',
                'phone' => null,
                'salary_type' => 'monthly',
                'base_salary' => 3000000.00,
                'rate_per_unit' => 0.00,
                'created_at' => '2026-01-14 00:34:32',
                'updated_at' => '2026-01-14 00:34:32',
            ],
            [
                'id' => 'a0d536e8-13b1-4ec5-9ae8-6b0c82b9e729',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'user_id' => null,
                'name' => 'Karyawan 2',
                'position' => 'General',
                'phone' => null,
                'salary_type' => 'daily',
                'base_salary' => 50000.00,
                'rate_per_unit' => 0.00,
                'created_at' => '2026-01-14 00:34:56',
                'updated_at' => '2026-01-14 00:34:56',
            ],
            [
                'id' => 'a0d5370a-07cd-4893-b647-153e9e17ceb2',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'user_id' => null,
                'name' => 'Karyawan 3',
                'position' => 'General',
                'phone' => null,
                'salary_type' => 'borongan_item',
                'base_salary' => 0.00,
                'rate_per_unit' => 3000.00,
                'created_at' => '2026-01-14 00:35:18',
                'updated_at' => '2026-01-14 00:35:18',
            ],
        ]);

        // 7. Suppliers
        DB::table('suppliers')->insert([
            [
                'id' => 'a0d515b7-33c6-4d27-a7f1-03f0f8db6045',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'name' => 'Toko Griya',
                'phone' => '-',
                'address' => 'Bekasi',
                'created_at' => '2026-01-13 23:02:08',
                'updated_at' => '2026-01-13 23:02:08',
            ],
            [
                'id' => 'a0d515ce-8c84-49ee-8de7-48b10b3a5adb',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'branch_id' => null,
                'name' => 'Raja Sabun',
                'phone' => '-',
                'address' => 'Shoope',
                'created_at' => '2026-01-13 23:02:23',
                'updated_at' => '2026-01-13 23:02:23',
            ],
        ]);

        // 8. Inventory Items
        DB::table('inventory_items')->insert([
            [
                'id' => 'a0d514c6-6ab3-44d6-8230-c82da06f11a5',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Sabun',
                'unit' => 'ml',
                'stock' => 5000.00,
                'avg_cost' => 16.00,
                'created_at' => '2026-01-13 22:59:30',
                'updated_at' => '2026-01-14 01:26:37',
            ],
            [
                'id' => 'a0d5152a-ab79-4848-a08b-2146d796c1cb',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Plastik Bungkus Besar',
                'unit' => 'pcs',
                'stock' => 50.00,
                'avg_cost' => 300.00,
                'created_at' => '2026-01-13 23:00:35',
                'updated_at' => '2026-01-13 23:00:35',
            ],
            [
                'id' => 'a0d5154e-fbaf-4e24-b1b2-48a472a0d11b',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Pelembut',
                'unit' => 'ml',
                'stock' => 3000.00,
                'avg_cost' => 20.00,
                'created_at' => '2026-01-13 23:00:59',
                'updated_at' => '2026-01-14 01:48:59',
            ],
            [
                'id' => 'a0d5156b-f0a5-494f-be37-a75e34e0edf7',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Pewangi',
                'unit' => 'ml',
                'stock' => 2000.00,
                'avg_cost' => 18.50,
                'created_at' => '2026-01-13 23:01:18',
                'updated_at' => '2026-01-13 23:04:16',
            ],
        ]);

        // 9. Services
        DB::table('services')->insert([
            [
                'id' => 'a0d537ec-33a3-400d-b481-1204e68c1263',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Cuci Kering Gosok',
                'price' => 6000.00,
                'unit' => 'kg',
                'created_at' => '2026-01-14 00:37:47',
                'updated_at' => '2026-01-14 00:38:16',
            ],
            [
                'id' => 'a0d5559c-a8b7-4362-8185-eabe2a2791ee',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'name' => 'Setrika',
                'price' => 5000.00,
                'unit' => 'kg',
                'created_at' => '2026-01-14 02:00:48',
                'updated_at' => '2026-01-14 02:00:48',
            ],
        ]);

        // 10. Service Materials
        DB::table('service_materials')->insert([
            [
                'id' => 'a0d53818-be9f-43b9-8925-cbaa1a7d757d',
                'service_id' => 'a0d537ec-33a3-400d-b481-1204e68c1263',
                'inventory_item_id' => 'a0d514c6-6ab3-44d6-8230-c82da06f11a5',
                'quantity' => 150.00,
                'created_at' => '2026-01-14 00:38:16',
                'updated_at' => '2026-01-14 00:38:16',
            ],
            [
                'id' => 'a0d53818-c00c-4737-be7a-a0651fab8e0e',
                'service_id' => 'a0d537ec-33a3-400d-b481-1204e68c1263',
                'inventory_item_id' => 'a0d5154e-fbaf-4e24-b1b2-48a472a0d11b',
                'quantity' => 50.00,
                'created_at' => '2026-01-14 00:38:16',
                'updated_at' => '2026-01-14 00:38:16',
            ],
            [
                'id' => 'a0d53818-c02f-4e8c-a862-b0375b85d0c2',
                'service_id' => 'a0d537ec-33a3-400d-b481-1204e68c1263',
                'inventory_item_id' => 'a0d5156b-f0a5-494f-be37-a75e34e0edf7',
                'quantity' => 50.00,
                'created_at' => '2026-01-14 00:38:16',
                'updated_at' => '2026-01-14 00:38:16',
            ],
        ]);

        // 11. System Settings
        DB::table('system_settings')->updateOrInsert(
            ['key' => 'branch_addon_price'],
            [
                'value' => '50000',
                'type' => 'decimal',
                'group' => 'subscription',
                'label' => 'Biaya Addon Cabang',
                'description' => 'Biaya tambahan per satu cabang per bulan.',
                'updated_at' => now(),
            ]
        );

        // 12. Purchase Orders
        DB::table('purchase_orders')->insert([
            [
                'id' => 'a0d5167b-40ff-4b72-8487-136d7e83d223',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'supplier_id' => 'a0d515b7-33c6-4d27-a7f1-03f0f8db6045',
                'total_amount' => 71000.00,
                'status' => 'received',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'paid_amount' => 0.00,
                'remaining_amount' => 0.00,
                'due_date' => null, // ✅ TAMBAHAN
                'created_at' => '2026-01-13 23:04:16',
                'updated_at' => '2026-01-13 23:04:16',
            ],
            [
                'id' => 'a0d54963-afa2-4ca4-ba31-60b0aaabf6f2',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'supplier_id' => 'a0d515b7-33c6-4d27-a7f1-03f0f8db6045',
                'total_amount' => 32000.00,
                'status' => 'received',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
                'paid_amount' => 32000.00,
                'remaining_amount' => 0.00,
                'due_date' => null, // ✅ TAMBAHAN
                'created_at' => '2026-01-14 01:26:37',
                'updated_at' => '2026-01-14 01:26:37',
            ],
            [
                'id' => 'a0d55151-ae30-4ffe-beb7-935190c691fd',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'supplier_id' => 'a0d515ce-8c84-49ee-8de7-48b10b3a5adb',
                'total_amount' => 20000.00,
                'status' => 'received',
                'payment_status' => 'partial',
                'payment_method' => 'installment',
                'paid_amount' => 15000.00,
                'remaining_amount' => 5000.00,
                'due_date' => '2026-01-15',
                'created_at' => '2026-01-14 01:48:47',
                'updated_at' => '2026-01-14 01:49:26',
            ],
        ]);


        // 13. Purchase Order Items
        DB::table('purchase_order_items')->insert([
            ['id' => 'a0d5167b-5253-4cc7-88c5-a5dc0bbeb7a9', 'purchase_order_id' => 'a0d5167b-40ff-4b72-8487-136d7e83d223', 'inventory_item_id' => 'a0d514c6-6ab3-44d6-8230-c82da06f11a5', 'qty' => 2000.00, 'cost' => 16.00, 'created_at' => '2026-01-13 23:04:16'],
            ['id' => 'a0d5167b-5344-4a92-9a24-aa6cae730e2d', 'purchase_order_id' => 'a0d5167b-40ff-4b72-8487-136d7e83d223', 'inventory_item_id' => 'a0d5154e-fbaf-4e24-b1b2-48a472a0d11b', 'qty' => 1000.00, 'cost' => 20.00, 'created_at' => '2026-01-13 23:04:16'],
            ['id' => 'a0d5167b-5441-464e-a513-c608c5edf59f', 'purchase_order_id' => 'a0d5167b-40ff-4b72-8487-136d7e83d223', 'inventory_item_id' => 'a0d5156b-f0a5-494f-be37-a75e34e0edf7', 'qty' => 1000.00, 'cost' => 19.00, 'created_at' => '2026-01-13 23:04:16'],
            ['id' => 'a0d54963-b4a0-453c-ab77-e1de18799d19', 'purchase_order_id' => 'a0d54963-afa2-4ca4-ba31-60b0aaabf6f2', 'inventory_item_id' => 'a0d514c6-6ab3-44d6-8230-c82da06f11a5', 'qty' => 2000.00, 'cost' => 16.00, 'created_at' => '2026-01-14 01:26:37'],
            ['id' => 'a0d55163-8821-45ac-9d48-3d289accf7b2', 'purchase_order_id' => 'a0d55151-ae30-4ffe-beb7-935190c691fd', 'inventory_item_id' => 'a0d5154e-fbaf-4e24-b1b2-48a472a0d11b', 'qty' => 1000.00, 'cost' => 20.00, 'created_at' => '2026-01-14 01:48:59'],
        ]);

        // 14. Purchase Payments
        DB::table('purchase_payments')->insert([
            'id' => 'a0d5518c-fc96-47f4-a463-0511a677d577',
            'purchase_order_id' => 'a0d55151-ae30-4ffe-beb7-935190c691fd',
            'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
            'amount' => 5000.00,
            'payment_date' => '2026-01-14',
            'notes' => null,
            'created_at' => '2026-01-14 01:49:26',
            'updated_at' => '2026-01-14 01:49:26'
        ]);

        // 15. Expenses
        DB::table('expenses')->insert([
            ['id' => 'a0d5167b-5526-4344-bbdc-e241599977c6', 'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30', 'category' => 'Belanja Bahan', 'amount' => 71000.00, 'date' => '2026-01-14', 'description' => 'Pembelian bahan dari Toko Griya', 'created_at' => '2026-01-13 23:04:16'],
            ['id' => 'a0d54963-b69a-46d7-a889-03a9245a6162', 'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30', 'category' => 'Belanja Bahan', 'amount' => 32000.00, 'date' => '2026-01-14', 'description' => 'Pembelian bahan dari Toko Griya (Cash)', 'created_at' => '2026-01-14 01:26:37'],
            ['id' => 'a0d55151-b417-4be3-87e0-a3fa3b96b53b', 'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30', 'category' => 'Belanja Bahan', 'amount' => 10000.00, 'date' => '2026-01-14', 'description' => 'Pembelian bahan dari Raja Sabun (Installment)', 'created_at' => '2026-01-14 01:48:47'],
            ['id' => 'a0d5518c-fe6e-4db3-8fa9-f45f6b4664af', 'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30', 'category' => 'Bayar Hutang Bahan', 'amount' => 5000.00, 'date' => '2026-01-14', 'description' => 'Pembayaran cicilan ke Raja Sabun (PO: a0d55151)', 'created_at' => '2026-01-14 01:49:26'],
        ]);

        // 16. Transactions
        DB::table('transactions')->insert([
            [
                'id' => 'a0d555c7-f418-4aa2-9725-7972b79c1d49',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'customer_id' => null, // ✅ WAJIB
                'total_price' => 25000.00,
                'amount_paid' => 0.00,
                'total_cogs' => 0.00,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_scheme' => 'bayar_nanti',
                'created_at' => '2026-01-14 02:01:16',
            ],
            [
                'id' => 'a0d5573c-1c22-44cc-a9cd-4c943e0c3666',
                'tenant_id' => 'a0d4ffd9-3fc5-4a39-aa0e-cef7c24ffa30',
                'customer_id' => 'a0d53518-28d6-4abf-a877-c615be9cbec6',
                'total_price' => 50000.00,
                'amount_paid' => 0.00,
                'total_cogs' => 0.00,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_scheme' => 'bayar_nanti',
                'created_at' => '2026-01-14 02:05:20',
            ],
        ]);


        // 17. Transaction Items
        DB::table('transaction_items')->insert([
            ['id' => 'a0d555c7-f92e-431c-903f-5ad67e83f427', 'transaction_id' => 'a0d555c7-f418-4aa2-9725-7972b79c1d49', 'service_id' => 'a0d5559c-a8b7-4362-8185-eabe2a2791ee', 'qty' => 5.00, 'price' => 5000.00, 'created_at' => '2026-01-14 02:01:16'],
            ['id' => 'a0d5573c-20dc-4ce8-a693-a10a4c972a35', 'transaction_id' => 'a0d5573c-1c22-44cc-a9cd-4c943e0c3666', 'service_id' => 'a0d5559c-a8b7-4362-8185-eabe2a2791ee', 'qty' => 10.00, 'price' => 5000.00, 'created_at' => '2026-01-14 02:05:20'],
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
