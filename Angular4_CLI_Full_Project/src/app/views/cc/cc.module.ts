import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { AlertModule } from 'ngx-bootstrap/alert';
import { NgDatepickerModule } from 'ng2-datepicker';

import { contratosComponent } from './contratos/contratos.component';
import { centroCostosComponent } from './centroCostos/centroCostos.component';
import { ccRoutingModule } from './cc-routing.module';

@NgModule({
  imports: [
    ccRoutingModule,
    HttpClientModule,
    CommonModule,
  	FormsModule,
  	AlertModule.forRoot(),
    NgDatepickerModule
  ],
  declarations: [ 
  	contratosComponent,
	  centroCostosComponent
  ]
})
export class ccModule { }
