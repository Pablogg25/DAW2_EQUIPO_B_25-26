import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $usersController from "../../core/UsersController";


function UserFormPage(){
    const [userData,setUserData]=useState({});

    const navegar=useNavigate();

    const {id}=useParams();

    
    return (
        <div>
            <div>Formulario create/update usuarios</div>
        </div>
    );
}

export default UserFormPage;
