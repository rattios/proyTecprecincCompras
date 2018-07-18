import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { UsuariosComponent } from './usuarios.component';
import { PerfilComponent } from './perfil.component';

const routes: Routes = [
  {
    path: '',
    component: UsuariosComponent,
    data: {
      title: 'Usuarios'
    }
  },
  {
    path: 'perfil',
    component: PerfilComponent,
    data: {
      title: 'Perfil'
    }
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsuariosRoutingModule {}
