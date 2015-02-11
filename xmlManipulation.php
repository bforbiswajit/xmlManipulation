<html>
	<head>
		<title>
			XML Manipulation
		</title>
		<style>
			#div_form
			{
				padding-left:18em;
				padding-right:18em;
				padding-top:5em;
			}
			
			input[type="text"],select
			{
				margin-left:2em;
			}
		</style>
		<script type="text/javascript">
			var error = 1;
			function validator(element)
			{
				if(element.id == "name")
				{
					var pattern = /^[a-zA-Z]+(\s?\.?\s?[a-zA-Z]{1,30})+$/;
					if(pattern.test(element.value))
					{
						error = 0;
						element.style.backgroundColor = "white";
					}
					else
					{
						alert("Please Enter a Valid Name");
						element.style.backgroundColor = "#FFCCCC";
					}
				}
				else
				if(element.id == "empno")
				{
					var pattern = /^\d{4,8}$/;
					if(pattern.test(element.value))
					{
						error = 0;
						element.style.backgroundColor = "white";
					}
					else
					{
						alert("Please Enter a Valid Employee Code.");
						element.style.backgroundColor = "#FFCCCC";
					}
				}
				else
				if(element.id == "doj")
				{
					var pattern = /^\d{2}\/\d{2}\/\d{4}$/;
					if(pattern.test(element.value))
					{
						error = 0;
						element.style.backgroundColor = "white";
					}
					else
					{
						alert("Please Enter Joining Date In Mentioned Format.");
						element.style.backgroundColor = "#FFCCCC";
					}
				}
				else
				if(element.id == "ext")
				{
					var pattern = /^\d{4}$/;
					if(pattern.test(element.value))
					{
						error = 0;
						element.style.backgroundColor = "white";
					}
					else
					{
						alert("Please Enter a Valid Extension.");
						element.style.backgroundColor = "#FFCCCC";
					}
				}
			}
			
			function form_validation()
			{
				if(error == 0)
					return true;
				else
					return false;
			}
		</script>
	</head>
	<body>
		<div id="div_form">
			<form method="POST" action="xml_manipulation.php" onsubmit="return form_validation()">
			<fieldset><legend align="center"><b>: Employee Details :</b></legend>
				Full Name :<input type="text" name="name" id="name" onchange="validator(this)" style="margin-left:4.5em;" required></input><br/>
				Employee Code :<input type="text" name="empno" id="empno" onchange="validator(this)" required></input><br/>
				Technology :<select name="technology" id="technology" style="margin-left:4em;">
								<option value="">--Select--</option>
								<option value="PHP">PHP</option>
								<option value=".Net">.Net</option>
								<option value="Java">Java</option>
								<option value="Python">Python</option>
								<option value="E.C.M">E.C.M</option>
								<option value="C.R.M">C.R.M</option>
								<option value="B.D">BD</option>
							</select><br/>
				Joining Date:<input type="text" name="doj" id="doj" onchange="validator(this)" style="margin-left:4em;" required></input><small>(DD/MM/YYYY)</small><br/>
				Extension :<input type="text" name="ext" id="ext" onchange="validator(this)" style="margin-left:5em;" required></input><br/>
				<input type="submit" name="submit" value="Submit" style="margin-left:10em;"></input>
				<input type="reset" name="reset" value="Reset"></input>
			</fieldset>
			</form>
		</div>
		<?php
			function delete_node($xml,$root,$node)
			{
				$id = $node->getAttribute("id");
				/*$xml->removeChild($node);
				$root->removeChild($node);
				
				$xml->formatOutput = true;
				$xml->save("employees.xml") or die("Error");*/
				echo "$id Deleted Successfully";
			}
		
			if(isset($_POST['submit']))
			{
				//$xml->createElement, $xml->createTextNode, ->appendChild
				//$xml->createAttribute, attribute_node->value
				
				$status = "new";
				if(file_exists("employees.xml"))
				{
					$xml = new DOMDocument();
					$xml->preserveWhiteSpace = false;	
					$xml->load('employees.xml');
					$status = "existing";
					
					global $xml;
					$root = $xml->documentElement;
				}
				else
				{
					$xml = new DOMDocument("1.0", "UTF-8");
					
					$root = $xml->createElement("employees");
					$xml->appendChild($root);
				}
				
				
				$node = $xml->createElement("employee");
				$root->appendChild($node);

				$id   = $xml->createAttribute("id");
				$id->value = $_POST['empno'];
				$node->appendChild($id);
				

				$name   = $xml->createElement("name");
				$nameText = $xml->createTextNode($_POST['name']);
				$name->appendChild($nameText);
				$node->appendChild($name);


				$technology   = $xml->createElement("technology");
				$technologyText = $xml->createTextNode($_POST['technology']);
				$technology->appendChild($technologyText);
				$node->appendChild($technology);
				
				
				$doj   = $xml->createElement("doj");
				$dojText = $xml->createTextNode($_POST['doj']);
				$doj->appendChild($dojText);
				$node->appendChild($doj);
				
				
				$ext   = $xml->createElement("ext");
				$extText = $xml->createTextNode($_POST['ext']);
				$ext->appendChild($extText);
				$node->appendChild($ext);

				if($status == "existing")
				{
					$xml->formatOutput = true;
					$xml->save("employees.xml") or die("Error");
				}
				elseif($status == "new")
				{
					$xml->formatOutput = true;
					echo "<xmp>". $xml->saveXML() ."</xmp>";
					$xml->save("employees.xml") or die("Error");
				}
			}
			
			//-----Reading the XML File------//
			echo "<center>";
			if(file_exists("employees.xml"))
			{
				$xml = new DOMDocument();
				$xml->load('employees.xml');
				
				$root = $xml->documentElement;
				/*print $xml->saveXML();
				foreach ($xml->childNodes AS $item)
				{
					echo $item->nodeName . " = " . $item->nodeValue . "<br/>";
				}*/
				echo "<table border='1'><tr><th>Emp. Code</th><th>Name</th><th>Technology</th><th>D.O.J</th><th>Extension</th><th>Operation</th></tr>";
				$employee = $xml->getElementsByTagName("employee");
				foreach($employee as $emp_node)
				{
					$id = $emp_node->getAttribute("id");
					$name = $emp_node->getElementsByTagName("name")->item(0)->nodeValue;
					$technology = $emp_node->getElementsByTagName("technology")->item(0)->nodeValue;
					$doj = $emp_node->getElementsByTagName("doj")->item(0)->nodeValue;
					$ext = $emp_node->getElementsByTagName("ext")->item(0)->nodeValue;
					echo "<tr><td>$id</td><td>$name</td><td>$technology</td><td>$doj</td><td>$ext</td><td>";
		?>
					<input type='button' name='delete' id='delete' value='Delete' onclick="document.write('<?php delete_node($xml,$root, $emp_node);?>');"></input>
		<?php
				}
				echo "</td></tr></table>";
			}
			else
				echo "No Data Found.";
			echo "</center>";
			
			/*$xml = new DOMDocument("1.0");

			$root = $xml->createElement("data");
			$xml->appendChild($root);

			$id   = $xml->createElement("id");
			$idText = $xml->createTextNode('1');
			$id->appendChild($idText);

			$title   = $xml->createElement("title");
			$titleText = $xml->createTextNode('"PHP Undercover"');
			$title->appendChild($titleText);


			$book = $xml->createElement("book");
			$book->appendChild($id);
			$book->appendChild($title);

			$root->appendChild($book);

			$xml->formatOutput = true;
			echo "<xmp>". $xml->saveXML() ."</xmp>";

			$xml->save("mybooks.xml") or die("Error");*/

		?>
	</body>
</html>
