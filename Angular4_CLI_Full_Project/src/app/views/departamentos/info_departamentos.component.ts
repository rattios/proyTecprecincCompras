import { Component, OnInit, Input } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';
import { RutaService } from '../../services/ruta.service';

@Component({
  selector: 'app-info-departamentos',
  templateUrl: 'info_departamentos.component.html'
})
export class info_departamentosComponent {
  @Input() informacion:any;
  public departamento: any={
    nombre:'',
    telefono:''
  };
  public loading:any=false;
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      this.loading=true;

      this.http.get(this.ruta.get_ruta()+'departamentos/'+this.informacion)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.departamento=data;
             this.departamento=this.departamento.departamento[0];
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
    }

    public success:any;
    public fail:any;
    editar(){
      
      var send={
        nombre:this.departamento.nombre,
        telefono:this.departamento.telefono,
      }
      console.log(send);
      this.http.put(this.ruta.get_ruta()+'departamentos/'+this.informacion,send)
           .toPromise()
           .then(
           data => {
            
              console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
             

            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);

           });
    }

  
}
