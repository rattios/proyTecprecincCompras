import { NgModule } from '@angular/core';
import {CommonModule} from '@angular/common';
import { ChartsModule } from 'ng2-charts/ng2-charts';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { stockDepartamentoComponent } from './stockDepartamentos.component';
import { departamentosRoutingModule } from './departamentos-routing.module';
import { administracionComponent } from './administracion.component';
import { calidadComponent } from './calidad.component';
import { mantenimientoComponent } from './mantenimiento.component';
import { rrhhComponent } from './rrhh.component';
import { ventasComponent } from './ventas.component';
import { catrielComponent } from './catriel.component';
import { PHComponent } from './PH.component';
import { RDLSComponent } from './RDLS.component';
import { info_departamentoComponent } from './info_departamento.component';
import { info_departamentosComponent } from './info_departamentos.component';
import { info_usuariosComponent } from './info_usuarios.component';
import { AlertModule } from 'ngx-bootstrap/alert';
// Tabs Component
import { TabsModule } from 'ngx-bootstrap/tabs';

@NgModule({
  imports: [
    departamentosRoutingModule,
    ChartsModule,
    HttpClientModule,
    CommonModule,
    FormsModule,
    TabsModule,
    AlertModule.forRoot(),
  ],
  declarations: [ 
    stockDepartamentoComponent,
    administracionComponent,
    calidadComponent,
    mantenimientoComponent,
    rrhhComponent,
    ventasComponent,
    catrielComponent,
    PHComponent,
    RDLSComponent,
    info_departamentoComponent,
    info_usuariosComponent,
    info_departamentosComponent
   ]
})
export class departamentosModule { }
