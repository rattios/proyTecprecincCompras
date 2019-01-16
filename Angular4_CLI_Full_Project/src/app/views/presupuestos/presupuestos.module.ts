import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { AlertModule } from 'ngx-bootstrap/alert';
import { ModalModule } from 'ngx-bootstrap/modal';
import { MatDatepickerModule, MatNativeDateModule, DateAdapter } from '@angular/material';
import { DateFormat } from './date-format';
import { NgDatepickerModule } from 'ng2-datepicker';


import { presupuestosRoutingModule } from './presupuestos-routing.module';

import { presupuestoComponent } from './presupuesto/presupuesto.component';
import { presupuestosComponent } from './presupuestos/presupuestos.component';
import { comparar_presupuestosComponent } from './comparar_presupuestos/comparar_presupuestos.component';
import { minutasComponent } from './minutas/minutas.component';


@NgModule({
  imports: [
    presupuestosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule,
    AlertModule.forRoot(),
    ModalModule.forRoot(),
    MatDatepickerModule,
    MatNativeDateModule,
    NgDatepickerModule
  ],
  declarations: [ 

    presupuestoComponent,
    presupuestosComponent,
    comparar_presupuestosComponent,
    minutasComponent

    ],
    providers: [
     { provide: DateAdapter, useClass: DateFormat },
     MatNativeDateModule,
   ],
})
export class presupuestosModule { 
   constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }

}