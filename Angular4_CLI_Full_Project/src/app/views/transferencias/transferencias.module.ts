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

import { transferenciasComponent } from './transferencias.component';
import { transferenciasRoutingModule } from './transferencias-routing.module';

import { transInfoComponent } from './transInfo.component';

import { transferenciasPuraComponent } from './transferencias/transferenciasPura.component';
import { devolucionesComponent } from './transferencias/devoluciones.component';
import { transferenciasPatrimonialComponent } from './transferencias/transferenciasPatrimonial.component';
import { transferenciasInfoComponent } from './transferencias/transferenciasInfo.component';

@NgModule({
  imports: [
    transferenciasRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule,
    AlertModule.forRoot(),
    ModalModule.forRoot(),
    MatDatepickerModule,
    MatNativeDateModule
  ],
  declarations: [ 
  	transferenciasComponent,
    transInfoComponent,
    transferenciasPuraComponent,
    devolucionesComponent,
    transferenciasPatrimonialComponent,
    transferenciasInfoComponent
	]
})
export class transferenciasModule {
  constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }
 }
