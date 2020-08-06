<?php

use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OrganizacionSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(EmpleadoSeeder::class);
        $this->call(CorreoSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProgramaInstaladoSeeder::class);
        $this->call(IpSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(EquipoSeeder::class);
        $this->call(RouterSeeder::class);
        $this->call(ImpresoraSeeder::class);
        $this->call(ProgramaEquipoSeeder::class);
        $this->call(DetalleEquipoSeeder::class);
        $this->call(DetalleComponenteSeeder::class);
        $this->call(SolicitudSeeder::class);
        $this->call(MantenimientoSeeder::class);

    }
}
