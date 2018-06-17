import { Component, OnInit, Input } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';
import { RutaService } from '../../services/ruta.service';

@Component({
  selector: 'app-info-usuarios',
  templateUrl: 'info_usuarios.component.html'
})
export class info_usuariosComponent {
  @Input() informacion:any;
  public prov: any;
  public stock: any;
  public proveedores: any;
  public productos: any;
  public departamentos: any;
  public proveedor: any='';
  public loading=true;
  public verDatos=false;
  public success=false;
  public fail=false;
  public crear=false;
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
      this.loading=true;

      this.http.get(this.ruta.get_ruta()+'usuarios?rol='+10+"&departamento_id="+this.informacion)
           .toPromise()
           .then(
           data => {
             this.prov=data;
           	  this.proveedores=this.prov;
              for (var i = 0; i < this.proveedores.length; ++i) {
                if(this.proveedores[i].rol==0) {
                  this.proveedores[i].nombreRol='ADMIN';
                }else if(this.proveedores[i].rol==1) {
                  this.proveedores[i].nombreRol='SUPERVISOR';
                }else if(this.proveedores[i].rol==2) {
                  this.proveedores[i].nombreRol='EMPLEADO';
                }
              }
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


      this.http.get(this.ruta.get_ruta()+'departamentos')
           .toPromise()
           .then(
           data => {
             this.departamentos=data;
               this.departamentos=this.departamentos.departamentos;
              
              console.log(this.departamentos);
              
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
    }

    ver(item){
      if(item==0) {
        this.proveedor={
          nombre:"",
          apellido:"",
          email:"",
          telefono:"",
          departamento_id:"",
          rol: 2,
          user:"",
          password:"",
          id:0
        };
        this.crear=true;
        console.log(item);
      }else{
        this.proveedor=item;
        this.crear=false;
        console.log(item);
      }
    	
      this.verDatos=true;
    }
    volver(){
      this.verDatos=false;
    }
    
    crearProveedor(){
      
      var send={
        nombre:this.proveedor.nombre,
        apellido:this.proveedor.apellido,
        telefono: this.proveedor.telefono,
        email: this.proveedor.email,
        departamento_id: this.proveedor.departamento_id,
        rol: this.proveedor.rol,
        user: this.proveedor.user,
        password:this.proveedor.password
      }

      this.http.post(this.ruta.get_ruta()+'usuarios',send)
           .toPromise()
           .then(
           data => {
            
              console.log(data);
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);

              this.loading=true;
              
              this.ngOnInit();   

            },
           msg => { 
             console.log(msg);
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);

           });
    }



    editar(){
      
      var send={
        id:this.proveedor.id,
        nombre:this.proveedor.nombre,
        apellido:this.proveedor.apellido,
        telefono: this.proveedor.telefono,
        email: this.proveedor.email,
        departamento_id: this.proveedor.departamento_id,
        rol: this.proveedor.rol,
        user: this.proveedor.user
        //password:this.proveedor.password
      }
      console.log(send);
      this.http.put(this.ruta.get_ruta()+'usuarios/'+this.proveedor.id,send)
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
              
              if (this.productList[i].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].apellido.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].departamentos[0].nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].nombreRol.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
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

}
