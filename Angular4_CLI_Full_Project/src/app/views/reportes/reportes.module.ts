import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { MatDatepickerModule, MatNativeDateModule, DateAdapter } from '@angular/material';
import { DateFormat } from './date-format';

import { stockComponent } from './inventario/stock.component';
import { reportesRoutingModule } from './reportes-routing.module';
import { infoComponent } from './inventario/info.component';
import { trazabilidadComponent } from './trazabilidad/trazabilidad.component';
import { infotraComponent } from './trazabilidad/infotra.component';
import { departComponent } from './departamentos/depart.component';
import { infodepartComponent } from './departamentos/infodepart.component';
import { empleadosComponent } from './empleados/empleados.component';
import { infoempleadosComponent } from './empleados/infoempleados.component';

@NgModule({
  imports: [
    reportesRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule.forRoot(),
    MatDatepickerModule,
    MatNativeDateModule
  ],
  declarations: [ 
  	stockComponent,
    infoComponent,
    trazabilidadComponent,
    infotraComponent,
    departComponent,
    infodepartComponent,
    empleadosComponent,
    infoempleadosComponent
   ],
   providers: [
     { provide: DateAdapter, useClass: DateFormat },
     MatNativeDateModule,
   ],
})
export class reportesModule {
  constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }
}
