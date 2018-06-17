import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

/*import { stockComponent } from './stock.component';
import { stock2Component } from './stock2.component';*/
import { stockDepartamentoComponent } from './stockDepartamentos.component';
import { administracionComponent } from './administracion.component';
import { calidadComponent } from './calidad.component';
import { mantenimientoComponent } from './mantenimiento.component';
import { rrhhComponent } from './rrhh.component';
import { ventasComponent } from './ventas.component';
import { catrielComponent } from './catriel.component';
import { PHComponent } from './PH.component';
import { RDLSComponent } from './RDLS.component';

const routes: Routes = [

      {
        path: 'compras',
        component: stockDepartamentoComponent,
        data: {
          title: 'Stock de compras'
        }
      },
      {
        path: 'administracion',
        component: administracionComponent,
        data: {
          title: 'Stock de administración'
        }
      },
      {
        path: 'calidad',
        component: calidadComponent,
        data: {
          title: 'Stock de calidad'
        }
      },
      {
        path: 'mantenimiento',
        component: mantenimientoComponent,
        data: {
          title: 'Stock de mantenimiento'
        }
      },
      {
        path: 'rrhh',
        component: rrhhComponent,
        data: {
          title: 'Stock de rrhh'
        }
      },
      {
        path: 'ventas',
        component: ventasComponent,
        data: {
          title: 'Stock de ventas'
        }
      },
      {
        path: 'catriel',
        component: catrielComponent,
        data: {
          title: 'Stock de ventas'
        }
      },
      {
        path: 'ph',
        component: PHComponent,
        data: {
          title: 'Stock de ventas'
        }
      },
      {
        path: 'rdls',
        component: RDLSComponent,
        data: {
          title: 'Stock de ventas'
        }
      }
   
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class departamentosRoutingModule {}
