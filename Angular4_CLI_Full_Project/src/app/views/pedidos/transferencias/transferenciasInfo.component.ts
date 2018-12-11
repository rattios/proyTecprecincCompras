import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { Router } from '@angular/router';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';
import { RutaService } from '../../../services/ruta.service';

@Component({
  selector: 'app-transferencias-info',
  templateUrl: 'transferenciasInfo.component.html'
})
export class transferenciasInfoComponent {


  @Input() informacion:any;

  constructor(private http: HttpClient, private ruta: RutaService, private router: Router) {
   this.http.get(this.ruta.get_ruta()+'login/check?token='+localStorage.getItem('tecprecinc_token'))
         .toPromise()
         .then(
         data => {
           
           console.log(data);
           var usr:any='';
           usr=data;
           if(usr.rol!=0) {
             this.router.navigate(['pages/login'], {});
             alert('Usuario no autorizado.');
           }
          },
         msg => { 
           console.log(msg);
           this.router.navigate(['pages/login'], {});
           //this.loading=false;
           alert('Usuario no autorizado.');
         });
  }

   ngOnInit(): void {
      console.log(this.informacion);
      
      if(this.informacion!=undefined) {
       
      }
    }

  aceptar(item){
    console.log(item);

    var send = {
      
    }

    this.http.post(this.ruta.get_ruta()+'transferencias/aprobar/'+item.id,send)
         .toPromise()
         .then(
         data => {
           console.log(data);
           item.estado=2;
           item.estado2='Aprobada';
           alert('Exito');
          },
         msg => { 
           console.log(msg);
           alert(msg.error);
         });
  }
    
}
