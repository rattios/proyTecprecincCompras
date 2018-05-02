import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { AlertModule } from 'ngx-bootstrap/alert';
import { ModalModule } from 'ngx-bootstrap/modal';

import { UsuariosComponent } from './usuarios.component';
import { UsuariosRoutingModule } from './usuarios-routing.module';

@NgModule({
  imports: [
    UsuariosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
	AlertModule.forRoot(),
    ModalModule.forRoot()
  ],
  declarations: [ 
  	UsuariosComponent
    ]
})
export class UsuariosModule { }
