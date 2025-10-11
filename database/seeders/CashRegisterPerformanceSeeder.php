<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\CashRegister;
use App\Models\Payment;

class CashRegisterPerformanceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $cashRegistersToCreate = 50;   // cajas
        $minSalesPerCash = 10;
        $maxSalesPerCash = 40;
        $maxItemsPerSale = 6;

        DB::disableQueryLog(); 

        //Crear usuarios base (si no existen)
        if (User::count() < 5) {
            User::create([
                'name' => 'Admin Test',
                'email' => 'admin@example.test',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);
            User::create([
                'name' => 'Cajero Test',
                'email' => 'cajero@example.test',
                'password' => Hash::make('password'),
                'role' => 'cajero'
            ]);
            User::create([
                'name' => 'Vendedor Test',
                'email' => 'vendedor@example.test',
                'password' => Hash::make('password'),
                'role' => 'vendedor'
            ]);
            // Un par más random
            for ($i = 0; $i < 2; $i++) {
                User::create([
                    'name' => $faker->name,
                    'email' => 'user'.$i.'@example.test',
                    'password' => Hash::make('password'),
                    'role' => $faker->randomElement(['cajero', 'vendedor'])
                ]);
            }
        }

        $users = User::all();

        // 1) Crear algunas mesas si no hay
        if (Table::count() < 10) {
            for ($i = 1; $i <= 12; $i++) {
                Table::create([
                    'name' => "Mesa {$i}",
                    'status' => 'Disponible'
                ]);
            }
        }
        $tables = Table::all();

        // 2) Crear productos si no hay
        if (Product::count() < 20) {
            $categories = ['bebidas', 'cocteles', 'snacks', 'entrada', 'otro'];
            for ($i = 1; $i <= 40; $i++) {
                Product::create([
                    'name' => ucfirst($faker->words(2, true)),
                    'category' => $faker->randomElement($categories),
                    'price' => $faker->randomFloat(2, 1000, 200000),
                    'stock' => $faker->numberBetween(5, 200),
                    'min_stock' => $faker->numberBetween(1, 10),
                ]);
            }
        }
        $products = Product::all();

        // 3) Crear cajas (cash_registers)
        $cashRegisters = collect();
        for ($c = 0; $c < $cashRegistersToCreate; $c++) {
            $user = $users->where('role', 'cajero')->count() ? $users->where('role', 'cajero')->random()
                                                           : $users->random();

            $openedAt = $faker->dateTimeBetween('-15 days', '-1 days');
            // 20% de cajas quedan abiertas (closed_at = null)
            $isOpen = $faker->boolean(20);
            $closedAt = $isOpen ? null : (clone $openedAt)->modify('+'.rand(2, 14).' hours');

            $openingAmount = $faker->randomFloat(2, 20000, 200000);

            $cash = CashRegister::create([
                'user_id' => $user->id,
                'opened_at' => $openedAt,
                'closed_at' => $closedAt,
                'opening_amount' => $openingAmount,
                // closing_amount y difference se actualizarán despues
            ]);

            $cashRegisters->push($cash);
        }

        // Comprueba si payments tiene la columna cash_register_id
        $paymentsHasCashRegister = Schema::hasColumn('payments', 'cash_register_id');

        // 4) Crear ventas para cada caja y payments asociados CON SALEDETAILS válidos
        foreach ($cashRegisters as $cash) {
            $salesCount = rand($minSalesPerCash, $maxSalesPerCash);
            $totalPaymentsForCash = 0;

            for ($s = 0; $s < $salesCount; $s++) {
                // asignar vendedor aleatorio
                $vendedor = $users->where('role', 'vendedor')->count() ? $users->where('role', 'vendedor')->random()
                                                                       : $users->random();

                // fecha de la venta entre apertura y cierre (o entre apertura y ahora si abierta)
                $saleDate = $cash->closed_at ? $faker->dateTimeBetween($cash->opened_at, $cash->closed_at)
                                             : $faker->dateTimeBetween($cash->opened_at, 'now');

                // crear venta (total temporal 0, se actualizará)
                $sale = Sale::create([
                    'user_id' => $vendedor->id,
                    'table_id' => $tables->random()->id,
                    'cash_register_id' => $cash->id,
                    'total' => 0,
                    'status' => $faker->randomElement(['pendiente', 'pagado']),
                    'payment_method' => null,
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);

                // agregar entre 1 y maxItemsPerSale detalles
                $itemsCount = rand(1, $maxItemsPerSale);
                $saleTotal = 0;

                $productPool = $products->shuffle();

                for ($it = 0; $it < $itemsCount; $it++) {
                    $prod = $productPool->get($it);
                    if (!$prod) break;

                    $qty = rand(1, min(5, max(1, $prod->stock)));
                    $unitPrice = $prod->price;
                    $subtotal = $qty * $unitPrice;

                    // crear detalle
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                        'created_at' => $saleDate,
                        'updated_at' => $saleDate,
                    ]);

                    $saleTotal += $subtotal;
                }

                // actualizar total de la venta
                $sale->update([
                    'total' => $saleTotal
                ]);

                // Crear payment si la venta está 'pagado' o con cierta probabilidad
                if ($sale->status === 'pagado' || $faker->boolean(70)) {
                    $paymentMethod = $faker->randomElement(['efectivo', 'transferencia']);
                    $paidAt = $faker->dateTimeBetween($saleDate, $cash->closed_at ?? 'now');

                    $paymentData = [
                        'sale_id' => $sale->id,
                        'user_id' => $vendedor->id,
                        'amount' => $saleTotal,
                        'payment_method' => $paymentMethod,
                        'paid_at' => $paidAt,
                        'created_at' => $paidAt,
                        'updated_at' => $paidAt,
                    ];

                    if ($paymentsHasCashRegister) {
                        $paymentData['cash_register_id'] = $cash->id;
                    }

                    Payment::create($paymentData);

                    $totalPaymentsForCash += $saleTotal;
                }
            }

            // actualizar cierre de caja si no está abierta (calculamos closing_amount)
            if ($cash->closed_at) {
                $cash->update([
                    'closing_amount' => $cash->opening_amount + $totalPaymentsForCash,
                    'difference' => ($cash->opening_amount + $totalPaymentsForCash) - ($cash->opening_amount + $totalPaymentsForCash), // placeholder si tienes otros gastos
                ]);
            } else {
                // si está abierta, opcionalmente actualizamos closing_amount a null o a lo actual
                $cash->update([
                    'closing_amount' => $cash->opening_amount + $totalPaymentsForCash,
                ]);
            }
        }

        $this->command->info("Seed completo: cajas ({$cashRegisters->count()}) con ventas y pagos asociados generados.");
    }
}
