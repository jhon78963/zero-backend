<?php

namespace Database\Seeders;

use App\Models\CourseCompetencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\View;

class CourseCompetenciaSeeder extends Seeder
{
    public function run()
    {
        $competencia = new CourseCompetencia();
        $competencia->description = 'Se comunica oralmente en su lengua materna';
        $competencia->course_id = 1;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Lee diversos tipos de textos escritos en su lengua materna';
        $competencia->course_id = 1;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Escribe diversos tipos de textos en su lengua materna';
        $competencia->course_id = 1;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de cantidad';
        $competencia->course_id = 2;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas deregularidad, equivalencia y cambio';
        $competencia->course_id = 2;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de forma, movimiento y localización';
        $competencia->course_id = 2;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Resuelve problemas de gestión de datos e incertidumbre';
        $competencia->course_id = 2;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Indaga mediante métodos científicos para construir sus conocimientos';
        $competencia->course_id = 3;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Explica el mundo físico basándose en conocimientos sobre los seres vivos, materia y energía, biodiversidad, Tierra y universo';
        $competencia->course_id = 3;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Diseña y construye soluciones tecnológicas para resolver problemas de entorno';
        $competencia->course_id = 3;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye su identidad';
        $competencia->course_id = 4;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Convive y participa democráticamente en la búsqueda del bien común';
        $competencia->course_id = 4;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye interpretaciones históricas';
        $competencia->course_id = 4;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Gestiona responsablemente el espacio y el ambiente';
        $competencia->course_id = 4;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Gestiona responsablemente los recursos económicos';
        $competencia->course_id = 4;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Apreca de manera crítica manifestaciones artístico-culturales';
        $competencia->course_id = 5;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Crea proyectos desde los lenguajes artísticos';
        $competencia->course_id = 5;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente, comprendiendo la doctrina de su propia religión. abierto al diálogo con las que le son cercanas';
        $competencia->course_id = 6;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Asume la experiencia del encuentro personal y comunitario con Dios en su proyecto de vida en coherencia con su creencia religiosa';
        $competencia->course_id = 6;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Se desenvuelve de manera autónoma a través de su motricidad';
        $competencia->course_id = 7;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Asume una vida saludable';
        $competencia->course_id = 7;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Interactúa a través de sus habilidades sociomotrices';
        $competencia->course_id = 7;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Se comunica oralmente en ingés como lengua extranjera';
        $competencia->course_id = 8;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Lee diversos tipos de textos escritos en inglés como lengua extranjera';
        $competencia->course_id = 8;
        $competencia->TenantId = 1;
        $competencia->save();

        $competencia = new CourseCompetencia();
        $competencia->description = 'Escribe diversos tipos de textos en inglés como lengua extranjera';
        $competencia->course_id = 8;
        $competencia->TenantId = 1;
        $competencia->save();
    }
}
