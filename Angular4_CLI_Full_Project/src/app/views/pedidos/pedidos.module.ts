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

import { SharedService } from './shared.service';

import { pedidosComponent } from './pedidos.component';
import { pedidosServicioComponent } from './pedidosServicio.component';
import { creadosComponent } from './creados/creados.component';
import { tablaInfoCreadosComponent } from './creados/tablaInfoCreados.component';

import { misPedidosComponent } from './mis-pedidos.component';
import { todosPedidosComponent } from './todos-pedidos.component';
import { infoComponent } from './info.component';
import { misInfoComponent } from './misInfo.component';
import { tablaInfoComponent } from './tablaInfo.component';
import { misTablaInfoComponent } from './misTablaInfo.component';
import { pedidosRoutingModule } from './pedidos-routing.module';
import { transferenciaComponent } from './transferencia.component';
import { transInfoComponent } from './transInfo.component';
import { presupuestoComponent } from './presupuesto/presupuesto.component';
import { pedidosStockComponent } from './pedidosStock/pedidos.component';

import { entrantesComponent } from './entrantes/entrantes.component';
import { tablaInfoEntrantesComponent } from './entrantes/tablaInfoEntrantes.component';

import { aprobarComponent } from './aprobar/aprobar.component';
import { tablaInfoAprobarComponent } from './aprobar/tablaInfoAprobar.component';

import { encursoComponent } from './encurso/encurso.component';
import { tablaInfoEncursoComponent } from './encurso/tablaInfoEncurso.component';

import { entregadosComponent } from './entregados/entregados.component';
import { tablaInfoEntregadosComponent } from './entregados/tablaInfoEntregados.component';

import { canceladosComponent } from './cancelados/cancelados.component';
import { tablaInfoCanceladosComponent } from './cancelados/tablaInfoCancelados.component';

import { transferenciasPuraComponent } from './transferencias/transferenciasPura.component';
import { transferenciasInfoComponent } from './transferencias/transferenciasInfo.component';

import { pickingComponent } from './picking.component';

import { presupuestosComponent } from './presupuesto.component';

import { NgxPermissionsModule } from 'ngx-permissions';

@NgModule({
  imports: [
    pedidosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule,
    AlertModule.forRoot(),
    ModalModule.forRoot(),
    MatDatepickerModule,
    MatNativeDateModule,
    NgxPermissionsModule.forRoot(),
  ],
  declarations: [ 
      pedidosComponent,
      pedidosServicioComponent,
      creadosComponent,
      tablaInfoCreadosComponent,
      misPedidosComponent,
      todosPedidosComponent,
    infoComponent,
    tablaInfoComponent,
    misTablaInfoComponent,
    misInfoComponent,
    transferenciaComponent,
    transInfoComponent,
    presupuestoComponent,
    pedidosStockComponent,
    aprobarComponent,
    tablaInfoAprobarComponent,
    entrantesComponent,
    tablaInfoEntrantesComponent,
    encursoComponent,
    tablaInfoEncursoComponent,
    entregadosComponent,
    tablaInfoEntregadosComponent,
    canceladosComponent,
    tablaInfoCanceladosComponent,
    transferenciasPuraComponent,
    transferenciasInfoComponent,
    pickingComponent
    ],
  providers: [ SharedService ],
})
export class pedidosModule {
  constructor(private dateAdapter:DateAdapter<Date>) {
    dateAdapter.setLocale('en-in'); // DD/MM/YYYY
  }
}