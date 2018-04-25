import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { AlertModule } from 'ngx-bootstrap/alert';

import { contratosComponent } from './contratos/contratos.component';
import { centroCostosComponent } from './centroCostos/centroCostos.component';

@NgModule({
  imports: [
    ccModule,
    HttpClientModule,
    CommonModule,
	FormsModule,
	AlertModule.forRoot()
  ],
  declarations: [ 
  	contratosComponent,
	centroCostosComponent
  ]
})
export class ccModule { }
