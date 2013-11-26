<div>
    <form method='post' action='validate'>
    <ul>
        <li>
            <label>Business Name: </label>
            <input type='text' name='txtName' value='<?=$this->input->post('txtName')?>' id='txtName' />
            <?=isset($txtName)?'<span class="formError">'.implode(' | ', $txtName).'</span>':''?>
        </li>
        <li>
            <label>Business Owner: </label>
            <input type='text' name='txtOwner' value='<?=$this->input->post('txtOwner')?>' id='txtOwner' />
            <?=isset($txtOwner)?'<span class="formError">'.implode(' | ', $txtOwner).'</span>':''?>
        </li>
        <li>
            <label>Address: </label>
            <input type='text' name='txtAddress' value='<?=$this->input->post('txtAddress')?>' id='txtAddress' />
            <?=isset($txtAddress)?'<span class="formError">'.implode(' | ', $txtAddress).'</span>':''?>
        </li>
        <li>
            <label>Phone: </label>
            <input type='text' name='txtPhone' id='txtPhone' value='<?=$this->input->post('txtPhone')?>' />
            <?=isset($txtPhone)?'<span class="formError">'.implode(' | ', $txtPhone).'</span>':''?>
        </li>
        <li>
            <label>Email: </label>
            <input type='text' name='txtEmail' id='txtEmail' value='<?=$this->input->post('txtEmail')?>' />
            <?=isset($txtEmail)?'<span class="formError">'.implode(' | ', $txtEmail).'</span>':''?>
        </li>
       
        <li><input type='submit' name='btnSubmit' id='btnSubmit' value='Add client' /></li> 
    </ul>
    </form>    
</div>