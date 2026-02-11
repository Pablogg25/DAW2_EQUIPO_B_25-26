import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import "./UsersPage.css";
import $usersController from "../../core/UsersController";

function UsersPage() {
  const [users, setUsers] = useState([]);

  const navegar = useNavigate();

  const cargarDatos = async () => {
    console.log("Cargando datos");

    let datos = await $usersController.getUsers();

    if (datos.success) {
      console.log("DATOS RECIVIDOS");
      setUsers(datos.data);
    } else {
      console.log("ERROR: un error inesperado surgió al cargar datos");
      alert("Ha surgido un error al cargar datos. Compruebe logs.");

    }
  }

  const onCreateUser = () => {
    console.log("On create user");
    //TODO: crear formulario de propiedades
    navegar("/users/0");
  }

  const onEditUser = (userId) => {
    console.log("On edit user id: " + userId);
    if (userId) {
      //navegar al formulario
      navegar("/users/"+userId);
    }
  }

  const onDeleteUser = async (userId) => {
    console.log("on delete user: " + userId);

    if (userId) {
      if (confirm("¿Está seguro que desea borrar este usuario?")) {
        console.log("Eliminando usuario");
        //realizar petición de borrado

        let result=await $usersController.deleteUser(userId);

        //if success
        if(!result.success){
          alert("ERROR, no se ha podido procesar su petición");
        }else{
          cargarDatos();
        }
      }
    }
  }

  useEffect(() => {
    cargarDatos();
  }, [])


  return (
    <div>
      <div>usersPage</div>
      <div>Lista para realizar crud sobre usuarios</div>


      <div>
        <button onClick={() => {
          onCreateUser();
        }}>Crear Usuario</button>
      </div>

      <div>
        {/* lista usuarios */}
        <div className="tableRow">
          <div>
            <strong>Id</strong>
          </div>
          <div>
            <strong>nombre</strong>
          </div>
          <div>
            <strong>teléfono</strong>
          </div>
          <div>
            <strong>email</strong>
          </div>
          <div>
            <strong>dirección</strong>
          </div>
          <div>
            <strong>username</strong>
          </div>
          <div>
            <strong>rol</strong>
          </div>
          <div>
            <strong>fecha_registro</strong>
          </div>
          <div>
            <strong>Operaciones</strong>
          </div>
        </div>

        {/* filas */}

        {
          users.map((elemento) => {
            return (
              <div key={elemento["usuarioId"]} className="tableRow">
                <div>{elemento["usuarioId"]}</div>
                <div>{elemento["nombre"]}</div>
                <div>{elemento["telefono"]}</div>
                <div>{elemento["email"]}</div>
                <div>{elemento["direccion"]}</div>
                <div>{elemento["rol"]}</div>
                <div>{elemento["fecha_registro"]}</div>
                <div>
                  <button onClick={() => { onEditUser(elemento["usuarioId"]); }}>Ver/editar</button>
                  <button onClick={() => { onDeleteUser(elemento["usuarioId"]); }}>Eliminar</button>
                </div>
              </div>
            );
          }
          )
        }
        <div>
        </div>
      </div>

    </div>
  );
}

export default UsersPage;
