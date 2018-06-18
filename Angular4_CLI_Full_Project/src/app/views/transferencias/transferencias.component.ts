import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  templateUrl: 'transferencias.component.html'
})
export class transferenciasComponent {
  public prov: any;
  public centroCostos: any;
  public idCentroCostos: any=43;
  public contratos:any=[];
  public idContrato:any=1;
  public stock: any;
  public productos: any;
  public departamentos: any;
  public departamentosRespaldo: any;
  public proveedor: any='';
  public departamento=localStorage.getItem('tecprecinc_nombre');
  public departamento_id=localStorage.getItem('tecprecinc_departamento_id');
  public template:'http://localhost/template.gif';
  public loading=true;
  public success=false;
  public fail=false;
  public seTransfirio=true;
  public cantidad=0;
  public producto:any={
    nombre:''
  };
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      this.loading=true;
      let headers = new HttpHeaders();
      headers = headers.append("Authorization", "Bearer " + localStorage.getItem('tecprecinc_token'));
      //headers = headers.append("Content-Type", "application/json");
      console.log(this.departamento_id);
      this.http.get(this.ruta.get_ruta()+'stock/transferencias?token='+localStorage.getItem('tecprecinc_token'), {
            headers: headers
        }).toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.stock=this.prov.productos;
              this.departamentosRespaldo=this.prov.departamentos;
              console.log(this.stock);
              console.log(this.departamentosRespaldo);
              this.productList = this.stock;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
              
              
            },
           msg => { 
             console.log(msg);
              this.loading=false;
           });
    }

    setProductos(item){
      this.seTransfirio=true;
      console.log(item);
      this.cantidad=1;
      this.producto=item;
    }
    bienUso(item){
      if(item.tipo_id!=2) {
        alert('Este producto no es un bien de uso y no se puede transferir!');
      }
    }
    restaurarDepartamentos(){
      this.departamentos=this.departamentosRespaldo;
    }
    setDepartamentos(item){
      this.http.get(this.ruta.get_ruta()+'pedidos/ubicar/'+item.id)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.departamentos=data;
             this.departamentos=this.departamentos.departamentos;
             for (var i = 0; i < this.departamentos.length; i++) {
                if(this.departamentos[i].usuario.rol==0) {
                  this.departamentos[i].usuario.nombreRol='ADMIN';
                }else if(this.departamentos[i].usuario.rol==1) {
                  this.departamentos[i].usuario.nombreRol='SUPERVISOR';
                }else if(this.departamentos[i].usuario.rol==2) {
                  this.departamentos[i].usuario.nombreRol='EMPLEADO';
                }
              }
            },
           msg => { 
             this.departamentos=[];
             console.log(msg);
             
           });
    }
    checkCantidad(cantidad){
      console.log(cantidad);
      if(this.cantidad>=0) {
        if(this.producto.stock<cantidad && this.producto.stock2<cantidad) {
          alert('La cantidad no puede ser mayor a la del stock.');
          this.cantidad=0;
        }
      }else{
        this.cantidad=0;
      }
    }
    
    transferir(depar){
      this.seTransfirio=true;

      if(this.cantidad>0) {
        console.log(depar);
        var enviar = {
          cantidad_transf:this.cantidad,
          stock_id:this.producto.id,
          departamento_id:depar
        }
        console.log(enviar);

        setTimeout(() => {
          this.http.post(this.ruta.get_ruta()+'/transferencias/pura',enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 1000);
              this.seTransfirio=false;
              this.ngOnInit();
              var departReceptor:any;
              if(depar==100) {
                departReceptor=101;
              }else if(depar==101) {
                departReceptor=100;
              }
              this.traxas(this.producto.id,this.cantidad,depar,departReceptor,localStorage.getItem('tecprecinc_usuario_id'),localStorage.getItem('tecprecinc_usuario_id'),0,'Transferencia entre almacenes');

            },
           msg => { 
             console.log(msg);
             console.log(msg.error);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
        }, 1000);
      }else{
        alert('La cantidad debe ser mayor a cero.');
      }
        
    }

    devolver(depar,almacen,usuario){
      this.seTransfirio=true;
      if(this.cantidad>0) {
        console.log(depar);
        var enviar = {
          cantidad_transf:this.cantidad,
          stock_id:this.producto.id,
          departamento_id:depar,
          usuario_id:usuario,
          almacen:almacen
        }
        console.log(enviar);
        var departOrigen:any;
        if(almacen==1){
          departOrigen=100;
        }else{
          departOrigen=101;
        }

        setTimeout(() => {
          this.http.post(this.ruta.get_ruta()+'/transferencias/devolucion',enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.getTrasnf=data;
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
              this.seTransfirio=false;
              this.ngOnInit();
               this.traxas(this.producto.id,this.cantidad,depar,departOrigen,'',localStorage.getItem('tecprecinc_usuario_id'),this.getTrasnf.devolucion.id,'Devolución');
            },
           msg => { 
             console.log(msg);
             console.log(msg.error);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
        }, 1000);
      }else{
        alert('La cantidad debe ser mayor a cero.');
      }
        
    }
    public depart:any;
    public selecAlmacen=false;
    public empleados:any;
    public empleado_id:any;
    seleccionar(depar){
      this.depart=depar;
      console.log(this.depart);
      this.selecAlmacen=true;
      this.http.get(this.ruta.get_ruta()+'usuarios?rol='+10+"&departamento_id="+this.depart)
           .toPromise()
           .then(
           data => {
              this.prov=data;
              this.empleados=this.prov;
              for (var i = 0; i < this.empleados.length; i++) {
                if(this.empleados[i].rol==0) {
                  this.empleados[i].nombreRol='ADMIN';
                }else if(this.empleados[i].rol==1) {
                  this.empleados[i].nombreRol='SUPERVISOR';
                }else if(this.empleados[i].rol==2) {
                  this.empleados[i].nombreRol='EMPLEADO';
                }
              }
              console.log(this.empleados);
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      //console.log(this.informacion);

    }
    unSelecAlmacen(depar){
      this.depart=depar;
      this.selecAlmacen=false;
    }
    public getTrasnf:any;
    enviar(almacen){
      this.seTransfirio=true;
      if(this.cantidad>0) {
        console.log(almacen);
        var enviar = {
          cantidad_transf:this.cantidad,
          stock_id:this.producto.id,
          departamento_id:this.departamento_id,
          almacen:almacen,
          receptor_id:this.depart,
          usuario_id:this.empleado_id
        }
        console.log(enviar);
        var departOrigen:any;
        if(almacen==1){
          departOrigen=100;
        }else{
          departOrigen=101;
        }
        setTimeout(() => {
          this.http.post(this.ruta.get_ruta()+'/transferencias/patrimonial',enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.getTrasnf=data;
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);
              this.seTransfirio=false;
              this.ngOnInit();
              this.traxas(this.producto.id,this.cantidad,departOrigen,this.depart,localStorage.getItem('tecprecinc_usuario_id'),this.empleado_id,this.getTrasnf.transferencia.id,'Transferencia patrimonial');
            },
           msg => { 
             console.log(msg);
             console.log(msg.error);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
        }, 1000);
      }else{
        alert('La cantidad debe ser mayor a cero.');
      }
        
    }
    traxas(stock_id,cantidad,d_emisor_id,d_receptor_id,u_emisor_id,u_receptor_id,operacion_id,tipo){
      var sendTraza={
       stock_id:stock_id,
       cantidad:cantidad,
       d_emisor_id:d_emisor_id,
       d_receptor_id:d_receptor_id,
       u_emisor_id:u_emisor_id,
       u_receptor_id:u_receptor_id,
       operacion_id:operacion_id,
       tipo:tipo
      }
      console.log(sendTraza);
      this.http.post(this.ruta.get_ruta()+'trazas',sendTraza)
       .toPromise()
       .then(
       data => {
         console.log(data);
         console.log('éxito al registrar la traza');
        },
       msg => { 
         console.log(msg);
         console.log('Error: '+JSON.stringify(msg));
      });
    }

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 10;
   pageNumber : number = 0;
   currentIndex : number = 1;
   items: any;
   pagesIndex : Array<number>;
   pageStart : number = 1;
   inputName : string = '';

   init(){
         this.currentIndex = 1;
         this.pageStart = 1;
         this.pages = 4;

         this.pageNumber = parseInt(""+ (this.filteredItems.length / this.pageSize));
         if(this.filteredItems.length % this.pageSize != 0){
            this.pageNumber ++;
         }
    
         if(this.pageNumber  < this.pages){
               this.pages =  this.pageNumber;
         }
       
         this.refreshItems();
         console.log("this.pageNumber :  "+this.pageNumber);
   }
   FilterByName(){
      this.filteredItems = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; ++i) {

              if (this.productList[i].stock==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].precio==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].codigo==this.inputName) {
                 this.filteredItems.push(this.productList[i]);
              }
            }
      }else{
         this.filteredItems = this.productList;
      }
      console.log(this.filteredItems);
      this.init();
   }
   fillArray(): any{
      var obj = new Array();
      for(var index = this.pageStart; index< this.pageStart + this.pages; index ++) {
                  obj.push(index);
      }
      return obj;
   }
 refreshItems(){
               this.items = this.filteredItems.slice((this.currentIndex - 1)*this.pageSize, (this.currentIndex) * this.pageSize);
               this.pagesIndex =  this.fillArray();
   }
   prevPage(){
      if(this.currentIndex>1){
         this.currentIndex --;
      } 
      if(this.currentIndex < this.pageStart){
         this.pageStart = this.currentIndex;
      }
      this.refreshItems();
   }
   nextPage(){
      if(this.currentIndex < this.pageNumber){
            this.currentIndex ++;
      }
      if(this.currentIndex >= (this.pageStart + this.pages)){
         this.pageStart = this.currentIndex - this.pages + 1;
      }
 
      this.refreshItems();
   }
    setPage(index : number){
         this.currentIndex = index;
         this.refreshItems();
    }
}
