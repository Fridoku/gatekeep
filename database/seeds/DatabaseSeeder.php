<?php

use Illuminate\Database\Seeder as Seeder;

use App\Models\User;
use App\Models\Gate;
use App\Models\GateManager;
use App\Models\Token;



class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'fkutterer',
            'first_name' => 'Fridolin',
            'last_name' => 'Kutterer',
            'is_admin' => '1',
            'password_hash' => '$2y$10$wEv.f4Gvyqm.XeeOo3N2DOykYB8x1I5hGYfL33kwy9ASAL5Zzlj3i',
            'enabled' => '1',
            'ldap_uuid' => 'dummy',
        ])->save();

        User::create([
            'username' => 'ftester',
            'first_name' => 'Fritz',
            'last_name' => 'Tester',
            'is_admin' => '0',
            'password_hash' => '$2y$10$wEv.f4Gvyqm.XeeOo3N2DOykYB8x1I5hGYfL33kwy9ASAL5Zzlj3i',
            'enabled' => '1',
        ])->save();

        User::create([
            'username' => 'ktester',
            'first_name' => 'Klaus',
            'last_name' => 'Tester',
            'is_admin' => '0',
            'password_hash' => '$2y$10$wEv.f4Gvyqm.XeeOo3N2DOykYB8x1I5hGYfL33kwy9ASAL5Zzlj3i',
            'enabled' => '1',
            ])->save();


        GateManager::create([
            'name' => 'fsei_officedoor_controller',
            'api_key' => 'qbHfiTxBAUj74KbbVhwHCw2rBPMPf7MR',
            'enabled' => '1',
            ])->save();

        Gate::create([
            'name' => 'fsei_office_door',
            'nice_name' => 'FS-EI Bürotür',
            'gate_manager_id' => '1',
        ])->save();

        Gate::create([
            'name' => 'fsei_window',
            'nice_name' => 'FS-EI Fenster',
            'gate_manager_id' => '1',
        ])->save();

        Token::create([
            'name' => "Fridos Test Token",
            'token_hash' => "test123",
            'user_id' => 1,
        ])->save();

        Token::create([
            'name' => "Fritz Test Token",
            'token_hash' => "test1234",
            'user_id' => 2,
        ])->save();

        Token::create([
            'name' => "Klaus Test Token",
            'token_hash' => "test12345",
            'user_id' => 3,
        ])->save();

        $gate1 = Gate::find(1);
        $gate2 = Gate::find(2);
        User::find(1)->addGates($gate1);
        User::find(1)->addGates($gate2);
        User::find(2)->addGates($gate1);


    }
}
