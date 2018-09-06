<?php

use App\Dominio\Game\Game;
use Illuminate\Database\Seeder;
use App\Dominio\Contest\Contest;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{       

            DB::beginTransaction();
            // $this->call(UsersTableSeeder::class);
            $game1 = new Game;
            $game1->name = "MegaSena";
            $game1->save();
            
            $game2 = new Game;
            $game2->name = "Triângulo da sorte";
            $game2->save();


            $contest = new Contest;
            $contest->name = time()."_".$game1->id;
            $contest->price = 2.5;
            $contest->admin_tax = 0.5;
            $game1->contests()->save($contest);
            sleep(1);
            $contest = new Contest;
            $contest->name = time()."_".$game1->id;
            $contest->price = 2.5;
            $contest->admin_tax = 0.5;
            $game1->contests()->save($contest);
            sleep(1);
            
            $contest = new Contest;
            $contest->name = time()."_".$game2->id;
            $contest->price = 2.5;
            $contest->admin_tax = 0.5;
            $game2->contests()->save($contest);
            sleep(1);
            $contest = new Contest;
            $contest->name = time()."_".$game2->id;
            $contest->price = 2.5;
            $contest->admin_tax = 0.5;
            $game2->contests()->save($contest);
            sleep(1);
            
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();

        }

    }
}
