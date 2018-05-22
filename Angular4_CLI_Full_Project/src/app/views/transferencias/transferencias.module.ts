import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { AlertModule } from 'ngx-bootstrap/alert';
import { ModalModule } from 'ngx-bootstrap/modal';

import { transferenciasComponent } from './transferencias.component';
import { transferenciasRoutingModule } from './transferencias-routing.module';

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
  ],
  declarations: [ 
  	transferenciasComponent,
	]
})
export class transferenciasModule { }
