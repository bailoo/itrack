</td>
<td class="mystyle">
    <form method="POST" action="customer_plant_home.php" name="form1">
        <input type="hidden" name="filter_flag"  value="1">
        <div style="background-color:lightgrey" align="center"><h3>Filter Customer Plant</h3></center></div>

        <table align="left" width="100%">

            <tr><td>							
                    <table align="left">
                        <tr><td>&nbsp;<strong>Select Station Groups</strong></td></tr>
                        <?php
                        if ($All == 'on') {
                            echo '<tr><td><input type="checkbox" name="All" checked Onchange="javascript:selectUnselectAll();"> &nbsp;<font color="#303036"><strong>All</strong></font></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="All" Onchange="javascript:selectUnselectAll();"> &nbsp;<font color="#303036"><strong>All</strong></font></td></tr>';
                        }
                        if ($MS == 'on') {
                            echo '<tr><td><input type="checkbox" name="MS" checked> &nbsp;<div class="tooltip"><font color="#269900"><strong>MS</strong></font><span class="tooltiptext">(1->1999,<br>17001->19999)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="MS"> &nbsp;<div class="tooltip"><font color="#269900"><strong>MS</strong></font><span class="tooltiptext">(1->1999,<br>17001->19999)</span></div></td></tr>';
                        }

                        if ($KISOK == 'on') {
                            echo '<tr><td><input type="checkbox" name="KISOK" checked> &nbsp;<div class="tooltip"><font color="#990099"><strong>KISOK</strong></font><span class="tooltiptext">(14001->15999)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="KISOK"> &nbsp;<div class="tooltip"><font color="#990099"><strong>KISOK</strong></font><span class="tooltiptext">(14001->15999)</span></div></td></tr>';
                        }

                        if ($INSTITUTION == 'on') {
                            echo '<tr><td><input type="checkbox" name="INSTITUTION" checked> &nbsp;<div class="tooltip"><font color="#000099"><strong>INSTITUTION</strong></font><span class="tooltiptext">(1000001->1999999,<br>5000001->5999999)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="INSTITUTION"> &nbsp;<div class="tooltip"><font color="#000099"><strong>INSTITUTION</strong></font><span class="tooltiptext">(1000001->1999999,<br>5000001->5999999)</span></div></td></tr>';
                        }

                        if ($FS == 'on') {
                            echo '<tr><td><input type="checkbox" name="FS" checked> &nbsp;<div class="tooltip"><font color="#990000"><strong>FS</strong></font><span class="tooltiptext">(12001->13999,<br>16001->16999)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="FS"> &nbsp;<div class="tooltip"><font color="#990000"><strong>FS</strong></font><span class="tooltiptext">(12001->13999,<br>16001->16999)</span></div></td></tr>';
                        }

                        if ($DISTRIBUTOR == 'on') {
                            echo '<tr><td><input type="checkbox" name="DISTRIBUTOR" checked> &nbsp;<div class="tooltip"><font color="#007399"><strong>DISTRIBUTOR</strong></font><span class="tooltiptext">(70001->77999)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="DISTRIBUTOR"> &nbsp;<div class="tooltip"><font color="#007399"><strong>DISTRIBUTOR</strong></font><span class="tooltiptext">(70001->77999)</span></div></td></tr>';
                        }

                        if ($PLANTS == 'on') {
                            echo '<tr><td><input type="checkbox" name="PLANTS" checked> &nbsp;<div class="tooltip"><font color="#ff0000"><strong>PLANTS</strong></font><span class="tooltiptext">(ALL)</span></div></td></tr>';
                        } else {
                            echo '<tr><td><input type="checkbox" name="PLANTS"> &nbsp;<div class="tooltip"><font color="#ff0000"><strong>PLANTS</strong></font><span class="tooltiptext">(ALL)</span></div></td></tr>';
                        }
                        ?>
                        
                    </table>				
                </td>
            </tr>	
        </table>

        <div align="left"><input type="Submit" value="Show List >>"></div>

        <div align="left" style="background-color:lightgrey"><h4>Customer Plant List</h4></div>	

        <div id="customer_plant_id" overflow="auto" style="border: 1px solid red; height:340px; width:250px; position:relative; overflow:auto">
            
        </div>

    </form>

</td>
