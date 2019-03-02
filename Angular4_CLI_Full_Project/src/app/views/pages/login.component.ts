import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpParams  } from '@angular/common/http';
import { RutaService } from '../../services/ruta.service';
import 'rxjs/add/operator/toPromise';

@Component({
  templateUrl: 'login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  public usuario:any;
  public result:any;
  public loading:any=false;
  constructor(private http: HttpClient, private router: Router, private ruta: RutaService) {
  	this.usuario={
  		user:'',
  		password:''
  	}
  }

  login(){
  	console.log(this.usuario);
  	console.log(this.ruta.get_ruta()+'login/web');
    this.loading=true;
  	this.http.post(this.ruta.get_ruta()+'login/web', this.usuario)
        .toPromise()
        .then(
          data => { // Success
            console.log(data);
            this.result=data;
            console.log(this.result.user.departamento_id);
            localStorage.setItem('tecprecinc_token', this.result.token);
            localStorage.setItem('tecprecinc_nombre', this.result.user.nombre);
            localStorage.setItem('tecprecinc_usuario_id', this.result.user.id);
            localStorage.setItem('tecprecinc_departamento_id', this.result.user.departamento_id);
            localStorage.setItem('tecprecinc_apellido', this.result.user.apellido);
            localStorage.setItem('tecprecinc_rol', this.result.user.rol);
            this.http.get(this.ruta.get_ruta()+'usuarios2/'+this.result.user.id)
             .toPromise()
             .then(
             data => {
                var resp:any=data;
                console.log(resp[0].departamento2);
                var depart=resp[0].departamento2;
                localStorage.setItem('tecprecinc_departamentos', JSON.stringify(depart));
                this.loading=false;
              },
             msg => { 
               console.log(msg);
               this.loading=false;
             });
            setTimeout(()=>{    //<<<---    using ()=> syntax
                if(this.result.user.rol==0) {
                  this.router.navigate(['dashboard'], {});
                }else{
                  this.router.navigate(['pedidos/pedido'], {});
                }
                this.loading=false;
             },10);
            
            console.log(localStorage.getItem('tecprecinc_departamento_id'));
         },
          msg => { // Error
          	console.log(msg);
            this.loading=false;
          	alert('Error al iniciar sesión');
          }
        );
		//
	}
}
