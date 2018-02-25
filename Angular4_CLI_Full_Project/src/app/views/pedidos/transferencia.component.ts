import { Component, OnInit, Input } from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  selector: 'app-transferencia',
  templateUrl: 'transferencia.component.html'
})
export class transferenciaComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';

  @Input() producto:any;

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      console.log(this.producto);
      if(this.producto!=undefined) {
       
      }
    }

    
    picking(item){
      item.departamento=this.producto.usuario.departamento;
      console.log(item);
      //alert(JSON.stringify(item));
      var send = {
        picking: JSON.stringify(item)
      }

      this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
           .toPromise()
           .then(
           data => {
             console.log(data);
             var rec:any;
             rec=data;
             this.producto=rec.informacion;
            },
           msg => { 
             console.log(msg);
             
           });
    }
}
