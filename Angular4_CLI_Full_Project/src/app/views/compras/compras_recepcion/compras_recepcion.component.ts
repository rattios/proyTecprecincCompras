import { Component, OnInit, Input,Pipe, PipeTransform } from '@angular/core';
import {CommonModule, NgClass, DatePipe } from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';
import {MatDatepicker} from '@angular/material/datepicker';
import {DateAdapter} from '@angular/material';
import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';


@Component({
  templateUrl: 'compras_recepcion.component.html'
})
export class compras_recepcionComponent {
  public prov: any;
  public stock: any;
  public proveedores: any;
  public proveedores2: any;
  public productos: any;
  public loading=true;
  public loading2=true;
  public obs_recepcion: any;
  public proveedor: any={
          calificacion:null,
          cuit:"",
          email:"",
          estado:"",
          fax:"",
          habilitado:"NO",
          habilitado2: false,
          motivo:"",
          id:0,
          nombre_fantacia:"",
          productos:[],
          razon_social:"",
          telefono:"",
          direccion: "",
          formaPago: "",
          telefonos: []
        };
  public inicioD2:any=new Date();
  public finD2:any=new Date();

  constructor(private http: HttpClient, private ruta: RutaService) {

  }

  ngOnInit(): void {
    var principio:any= new Date();
    var mes:any=principio.getMonth()+1; 
    var anio:any=principio.getYear()+1900;
    principio=mes+"-01-"+anio;
    principio=new Date(principio);
    this.inicioD2=principio;
    console.log(principio);
    var datePipe = new DatePipe("en-US");
    console.log(datePipe.transform(new Date(), 'dd-MM-yyyy'));
    this.loading=true;
      this.http.get(this.ruta.get_ruta()+'compra?inicio='+datePipe.transform(principio, 'dd-MM-yyyy')+'&fin='+datePipe.transform(new Date(), 'dd-MM-yyyy'))
           .toPromise()
           .then(
           data => {
             this.prov=data;
              this.proveedores=this.prov.compra;
              console.log(this.proveedores);
              for (var i = 0; i < this.proveedores.length; i++) {
                if(this.proveedores[i].estado==0) {
                  this.proveedores[i].estado2='Enviado';
                }else if(this.proveedores[i].estado==1) {
                  this.proveedores[i].estado2='En proceso';
                }else if(this.proveedores[i].estado==2) {
                  this.proveedores[i].estado2='Recibido';
                }
              }
              this.productList = this.proveedores;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
  }

  buscar(){
    this.loading=true;
    var datePipe = new DatePipe("en-US");
    this.http.get(this.ruta.get_ruta()+'compra?inicio='+datePipe.transform(this.inicioD2, 'dd-MM-yyyy')+'&fin='+datePipe.transform(this.finD2, 'dd-MM-yyyy'))
         .toPromise()
         .then(
         data => {
           this.prov=data;
            this.proveedores=this.prov.compra;
            console.log(this.proveedores);
            this.productList = this.proveedores;
            this.filteredItems = this.productList;
            this.init();
            this.loading=false;
          },
         msg => { 
           console.log(msg);
           this.loading=false;
         });
  }
  public enviado:any={
      proveedor:{
        razon_social:'',
        nombre_fantasia:'',
        cuit:'',
        telefono:'',
        email:'',
      },
      pedido_id:0,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:''
    };
  public verDatos=false;
  ver(item){
    this.enviado=item;
    this.obs_recepcion=this.enviado.obs_recepcion;
    this.verDatos=true;
  }
  volver(){
    this.enviado={
      proveedor:{
        razon_social:'',
        nombre_fantasia:'',
        cuit:'',
        telefono:'',
        email:'',
      },
      pedido_id:0,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:''
    };
    this.verDatos=false;
  }
  public aPicking:any;
  public showPicking=false;
  selectPicking(item){
   // item.departamento=this.informacion.usuario.departamento;
    //item.informacion=this.informacion;
    console.log(item);
    this.aPicking=item;
    this.showPicking=true;
  }
  public aCargar:any=[];
  public ingresos:any=[];
  cargar(producto,id,cantidad,pedido_id,pedido){
    console.log(producto);
    if(producto.ingresos) {
      if(producto.ingresos.length==0) {
        producto.ingresos=[];
        console.log('entro en ingresos vacios');
      }
    }else{
      producto.ingresos=[];
    }
    
    this.aCargar.push(producto);
    this.ingresos= this.aCargar[0].ingresos;
    console.log(producto);
    console.log(this.aCargar);
  }
  cargarIngreso(item,id,cantidad,pedido_id,pedido){
    console.log(item);
    console.log(pedido);
    for (var i = 0; i < this.aCargar.length; i++) {
      if(this.aCargar[i].id==id) {
        this.aCargar[i].ingresos.push({
          cantidad:item.cantidad,
          cantidadAgregar:item.cantidadAgregar,
          nombre:item.nombre,
          precio:item.precio,
          remito:item.remito,
          factura:item.factura,
        });
        console.log(parseInt(item.cantidadAgregar));
        this.addInventario(id,parseInt(item.cantidadAgregar),pedido_id,pedido);
      }
    }
    console.log(this.aCargar);
  }

  ocultar(){
    this.aCargar=[];
    this.ingresos=[];
  }

  addInventario(id,cantidad,pedido_id,pedido){
    console.log(cantidad);
    console.log(pedido);
    var fac='';
    var prec=0;
    for (var i = 0; i < pedido.productos.length; i++) {
      if(pedido.productos[i].id==id) {
        pedido.productos[i].entregado=1;
        fac=pedido.productos[i].factura;
        prec=pedido.productos[i].precio;
      }
    }
    console.log(id+'-'+cantidad+'-'+pedido_id); 
    console.log('usuario id: '+localStorage.getItem('tecprecinc_usuario_id'));
    console.log(localStorage.getItem('tecprecinc_usuario_id'));
    var send={
      cantidad:cantidad,
      factura:fac,
      usuario:localStorage.getItem('tecprecinc_usuario_id'),
      pedido_id:pedido_id,
      precio:prec
    }
    this.http.post(this.ruta.get_ruta()+'add_inventario/'+id,send)
     .toPromise()
     .then(
     data => {
        var send2={
          estado:1,
          productos:pedido.productos,
          obs_recepcion:this.obs_recepcion
        }
        console.log(send2);
        this.http.put(this.ruta.get_ruta()+'compra/'+pedido_id,pedido)
         .toPromise()
         .then(
         data => {
            
            console.log(data);

            alert('Éxito!');

          },
         msg => { 
           console.log(msg);
           this.loading=false;
         });

      },
     msg => { 
       console.log(msg);
       this.loading=false;
     });
  }

  finalizar(enviado){
    var send={
      estado:2,
      productos:enviado.productos,
      obs_recepcion:this.obs_recepcion
    }
    this.http.put(this.ruta.get_ruta()+'compra/'+enviado.id,send)
         .toPromise()
         .then(
         data => {
            
            console.log(data);
            this.ngOnInit();
            this.verDatos=false;
            alert('Éxito!');
          },
         msg => { 
           console.log(msg);
           this.loading=false;
         }); 
  }


  

     public saveFile(){
      console.log('exportar');
      let printContents, popupWin;
    printContents = document.getElementById('exportable').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
          
          <style>
            @media print {
              @page { margin: 0; }
              body { margin: 1.6cm; }
              .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
               }
               .col-sm-12 {
                    width: 100%;
               }
               .col-sm-11 {
                    width: 91.66666667%;
               }
               .col-sm-10 {
                    width: 83.33333333%;
               }
               .col-sm-9 {
                    width: 75%;
               }
               .col-sm-8 {
                    width: 66.66666667%;
               }
               .col-sm-7 {
                    width: 58.33333333%;
               }
               .col-sm-6 {
                    width: 50%;
               }
               .col-sm-5 {
                    width: 41.66666667%;
               }
               .col-sm-4 {
                    width: 33.33333333%;
               }
               .col-sm-3 {
                    width: 25%;
               }
               .col-sm-2 {
                    width: 16.66666667%;
               }
               .col-sm-1 {
                    width: 8.33333333%;
               }
            }
          </style>
        </head>
      <body onload="window.print();window.close()"> ${printContents} </body>
      </html>`
    );
    popupWin.document.close();
  }




     //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 5;
   pageNumber : number = 6;
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

              if (this.productList[i].razon_social.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].cuit.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].telefono.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].email.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
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

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems2 : any;
   productList2 : any;
   pages2 : number = 4;
   pageSize2 : number = 5;
   pageNumber2 : number = 0;
   currentIndex2 : number = 1;
   items2: any;
   pagesIndex2 : Array<number>;
   pageStart2 : number = 1;
   inputName2 : string = '';

   init2(){
         this.currentIndex2 = 1;
         this.pageStart2 = 1;
         this.pages2 = 4;

         this.pageNumber2 = parseInt(""+ (this.filteredItems2.length / this.pageSize2));
         if(this.filteredItems2.length % this.pageSize2 != 0){
            this.pageNumber2 ++;
         }
    
         if(this.pageNumber2  < this.pages2){
               this.pages2 =  this.pageNumber2;
         }
       
         this.refreshItems2();
         console.log("this.pageNumber :  "+this.pageNumber2);
   }
   FilterByName2(){
      this.filteredItems2 = [];
      if(this.inputName2 != ""){
            for (var i = 0; i < this.productList2.length; ++i) {

              if (this.productList2[i].stock==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].nombre.toUpperCase().indexOf(this.inputName2.toUpperCase())>=0) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].precio==this.inputName2) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList2[i].codigo==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }
              
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems2 = this.productList2;
      }
      //console.log(this.filteredItems2);
      this.init2();
   }
   fillArray2(): any{
      var obj2 = new Array();
      for(var index2 = this.pageStart2; index2< this.pageStart2 + this.pages2; index2 ++) {
                  obj2.push(index2);
      }
      return obj2;
   }
 refreshItems2(){
               this.items2 = this.filteredItems2.slice((this.currentIndex2 - 1)*this.pageSize2, (this.currentIndex2) * this.pageSize2);
               this.pagesIndex2 =  this.fillArray();
               console.log(this.items2);
   }
   prevPage2(){
      if(this.currentIndex2>1){
         this.currentIndex2 --;
      } 
      if(this.currentIndex2 < this.pageStart2){
         this.pageStart2 = this.currentIndex2;
      }
      this.refreshItems2();
   }
   nextPage2(){
      if(this.currentIndex2 < this.pageNumber2){
            this.currentIndex2 ++;
      }
      if(this.currentIndex2 >= (this.pageStart2 + this.pages2)){
         this.pageStart2 = this.currentIndex2 - this.pages2 + 1;
      }
 
      this.refreshItems2();
   }
    setPage2(index : number){
         this.currentIndex2 = index;
         this.refreshItems2();
    }
    
}
