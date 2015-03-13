<?php

class Database {

    function Database($dbhost = DBSERVER, $dbuser = DBUSERNAME, $dbpwd = DBPASSWORD, $schema = DBSCHEMA) {

        if (!($this->_dB = mysql_connect($dbhost, $dbuser, $dbpwd, true))) {
            throw new Exception('Error al intentar conectar con la instancia de base de datos ' . $dbhost . ' -> ' . mysql_error());
        }
        if (!mysql_select_db($schema, $this->_dB)) {
            throw new Exception('Error al intentar conectar con el esquema ' . $schema . ' -> ' . mysql_error());
        }
        @mysql_query("set names utf8");
    }

    /**
     * Permite realizar cualquier consulta con la BBDD
     */
    function query($stmt, &$error = '', $offset = -1, $limit = 0) {
        SQLMonitor('INICIO: ' . $stmt, $this->table);
        $this->_stmt = $stmt;

        if (($offset > -1) && ($limit > 0)) {
            $this->_stmt .= " LIMIT $offset, $limit";
        } else {
            if ($limit > 0) {
                $this->_stmt .= " LIMIT $limit";
            }
        }
        $this->_errorNum = 0;
        $this->_cursor = mysql_query($this->_stmt, $this->_dB);
        if (!$this->_cursor) {
            $this->_errorNum = mysql_errno($this->_dB);
            if ($this->_errorMsg != '') {
                $this->_errorMsg .= '<hr/>';
            }

            $this->_errorMsg .= "<b>SQL Error $this->_errorNum: </b>" . mysql_error($this->_dB) . DEBUG_ENTER . DEBUG_ENTER . "<b>SQL= </b>$this->_stmt";
            $this->rollback();

            throw new Exception($this->_errorMsg);
        }
        SQLMonitor('FIN: ' . $stmt, $this->table);
        return $this->_cursor;
    }

    function getValue($stmt) {
        if (!$cur = $this->query($stmt)) {
            return null;
        }
        $r = null;
        if ($row = mysql_fetch_row($cur)) {
            $r = $row[0];
        }
        mysql_free_result($cur);
        return $r;
    }

    function getRecord($stmt) {
        $r = FALSE;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        if ($row = mysql_fetch_array($this->_cursor)) {
            $r = $row;
        }
        mysql_free_result($this->_cursor);
        return $r;
    }

    function getRecordSet($stmt, $result_type = MYSQL_BOTH) {
        $r = null;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        while ($row = mysql_fetch_array($this->_cursor, $result_type)) {
            $r[] = $row;
        }
        mysql_free_result($this->_cursor);

        return $r;
    }

    function getNamedRecordSet($stmt) {
        $r = null;
        if (!$this->_cursor = $this->query($stmt, $error)) {
            return null;
        }
        while ($row = mysql_fetch_array($this->_cursor, MYSQL_ASSOC)) {
            $r[] = $row;
        }
        mysql_free_result($this->_cursor);

        return $r;
    }

    public function insertNewID($stmt) {
        $r = '';
        if ($this->query($stmt)) {
            $r = mysql_insert_id($this->_dB);
            if ($r == FALSE) {
                $r = TRUE;
            }
        }
        return $r;
    }

    function getIDs($idfield, $table, $where = '', $order = '') {
        $foo = array();
        $q = 'SELECT ' . $idfield . ' FROM ' . $table;
        if ($where) {
            $q .= ' WHERE ' . $where;
        }
        if ($order) {
            $q .= ' ORDER BY ' . $order;
        }
        $r = $this->getRecordSet($q);
        if (count($r) > 0) {
            foreach ((array) $r as $rec) {
                $foo[] = $rec[$idfield];
            }
        }

        return $foo;
    }

    function getMinRow($table, $field) {
        $q = 'SELECT min(' . $field . ') FROM ' . $table;
        return $this->getValue($q);
    }

    function getMaxRow($table, $field) {
        $q = 'SELECT max(' . $field . ') FROM ' . $table;
        return $this->getValue($q);
    }

    function showTables() {
        $sql = 'SHOW FULL TABLES';
        return $this->
                        getRecordSet($sql);
    }

    function getAll($fields = '*', $where = false, $order = FALSE, $join = false, $limit = false, $group = false, $result_type = MYSQL_BOTH, $rename = false) {
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $count = ' FROM ' . $this->table;
        if ($rename) {
            $sql .= ' AS ' . $rename;

            $count .= ' AS ' . $rename;
        }
        if ($join) {
            $sql .= ' ' . $join . ' ';
            $count .= ' ' . $join . ' ';
        }
        if ($where) {
            $sql .= ' WHERE ' . $where;
            $count .= ' WHERE ' . $where;
        }

        if ($group) {
            $sql .= ' GROUP BY ' . $group;
            $count = 'SELECT COUNT(DISTINCT ' . $group . ')' . $count;
        } else {
            $count = 'SELECT COUNT(*)' . $count;
        }

        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }


        if ($this->table == 'tascas') {
//echo $sql;
        }
        $rs = $this->getRecordSet($sql
                , $result_type);
        $this->counting = $this->getValue($count);
        return $rs;
    }

    function getOne($id, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . $this->table;
        $sql .= ' WHERE ' . $this->fieldid . ' = ' . EC($id);
        $rs = $this->getRecord($sql);
        return $rs;
    }

    function saveItem($value, $files = array()) {
//        print_r($value);
//        die();
        $this->describeTable();
        $this->begin();


        if ($value['itemid'] == '-1') {
            $field_names = '';
            $field_values = '';
            foreach ((array) $this->fields as $key => $field) {

                if ($field['Field'] == $this->urlField) {
                    if (in_array($field ['Field'], $this->literalFields)) {
                        foreach ((array) $value[$field['Field']] as $langId => $foo) {
                            if ($langId != 'itemLiteralId') {

                                $value[$field['Field']][$langId] = strtourl($value[$this->convertToUrlField] [$langId]);
                            }
                        }
                    } else {
                        $value[$field['Field']] = strtourl($value[$this->convertToUrlField]);
                    }
                }
                if (in_array($field['Field'], $this->booleanFields)) {
                    if (isset($value[$field['Field']]) === false) {

                        $value[$field['Field']] == '';
                    }
                } else {
                    if (isset($value[$field['Field']]) === false || $key == 0) {
                        continue;
                    }
                }

                if (in_array($field['Field'], $this->
                                md5Fields)) {
                    $value[$field['Field']] = md5($value[$field['Field']]);
                }
                $field_names = concatena($field_names, $field['Field'], ', ');
                if (is_array($value[$field['Field']])) {
                    $field_values = concatena($field_values, trim($literals->saveLiteral($value[$field['Field']])), ', ');
                } else {
                    if ($value[$field['Field']] == 'NULL') {
                        $field_values = concatena($field_values, trim($value[$field['Field']]), ', ');
                    } else {
                        $field_values = concatena($field_values, $this->fridge(trim($value[$field['Field']])), ', ');
                    }
                }
            }

            $sql = 'INSERT INTO ' . $this->table . ' (' . $field_names . ')VALUES(' . $field_values . ')';
//echo $sql.DEBUG_ENTER;
            $ok = $this->insertNewID($sql);
            if ($ok == true) {
                $this->commit();
            } else {
                $this->rollback();
            }
        } else {
            $sql = 'UPDATE ' . $this->table . ' SET ';
            $thefields = '';
            foreach ((array) $this->fields as $key => $field) {
                if ($field['Field'] == 'itemStock') {
                    $value[$field['Field']] = trim($value[$field['Field']]);
                    $value[$field['Field']] = str_replace(' ', '', $value[$field['Field']]);
                    if ($value[$field['Field']] == '') {
                        $value[$field['Field']] = 'NULL';
                    }
                }
                if ($field['Field'] == $this->urlField) {
                    if (in_array($field['Field'], $this->literalFields)) {
                        foreach ((array) $value[$field['Field']] as $langId => $foo) {
                            if ($langId != 'itemLiteralId') {
                                $value[$field['Field']][$langId] = strtourl($value[$this->convertToUrlField][$langId]);
                            }
                        }
                    } else {
                        $value[$field['Field']] = strtourl($value[$this->convertToUrlField]);
                    }
                }
                if (in_array($field ['Field'], $this->booleanFields)) {
                    if (!isset($value[$field['Field']])) {
                        continue;
//$value[$field['Field']] == '';
                    }
                } else {
                    if (isset($value[$field['Field']]) === false || $key == 0) {
                        continue;
                    }
                }
                if ($old_value = array_search($field['Field'], $this->md5Fields)) {
                    if ($old_value != $value[$field['Field']]) {
                        $value[$field ['Field']] = trim(md5($value[$field['Field']]));
                    }
                }
                if (in_array($field['Field'], $this->literalFields)) {
                    $thefields = concatena($thefields, $field ['Field'] . ' = ' . $literals->saveLiteral($value[$field['Field']]), ', ');
                } else {
                    if ($value[$field['Field']] == 'NULL') {

                        $thefields = concatena($thefields, $field ['Field'] . ' = ' . trim($value[$field['Field']]), ', ');
                    } else {
                        $thefields = concatena($thefields, $field['Field'] . ' = ' . $this->fridge(trim($value[$field['Field']])), ', ');
                    }
                }
            }
            $sql .= $thefields;
            if ($thefields != '') {
                $sql .= ' WHERE ' . $this->fieldid . ' = ' . EC($value['itemid']);
//echo $sql.DEBUG_ENTER;
                $ok = $this->query($sql);
                if ($ok == true) {
                    $this->commit();
                    $ok = $value['itemid'];
                } else {
                    $this->rollback();
                }
            } else {
                $this->rollback();
                $ok = $value['itemid'];
            }
        } foreach ((array) $this->imageFields as $imageField) {
            if ($files[$imageField]['name']) {
                if ($newname = saveFile($files[$imageField]['name'], $files[$imageField]['tmp_name'], BASE_PATH . $this->imgFolder, $value[$imageField], $this->resize, true)) {
                    $sql = 'UPDATE ' . $this->table . ' SET ' . $imageField . ' = ' . $this->fridge($newname) . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($ok);
                    $this->query($sql);
                } else {
                    return 'Guardado ok, error con imagen';
                }
            }
        } return $ok;
    }

    function duplicateItem($table, $itemId, $father_field = false, $father_id = false) {
        $class = new $table();
        $main_item = $class->getOne($itemId);
        $item['itemid'] = '-1';
        foreach ((array) $main_item as $field => $value) {
            if (is_numeric($field)) {
                continue;
            }
            if ($field == $class->fieldid) {
                continue;
            }
            if (in_array($field, $class->imageFields)) {
                continue;
            } elseif (in_array($field, $class->avoidDuplication)) {
                continue;
            } elseif (in_array($field, $class->literalFields)) {
                $sql = 'SELECT literalLangFk,literalText 
                FROM literal_groups 
                LEFT JOIN literal_translations ON lit eralGroupId = literalGroupFk
                WHERE itemLiteralId = ' . EC($value);
                $translations = $class->getRecordSet($sql, MYSQLI_ASSOC);
                foreach ((array) $translations as $translation) {
                    $item[$field][$translation['literalLangFk']] = $translation ['literalText'];
                }
                if (count($item[$field]) > 0) {
                    $item[$field]['itemLiteralId'] = '';
                }
            } elseif ($field == $father_field) {
                $item[$field] = $father_id;
            } else {
                $item[$field] = $value;
            }
        }
        if ($class->table == 'item_items') {
            $duplicated_id = $class->saveItem($item, false, false);
        } else {
            $duplicated_id = $class->saveItem($item);
        } foreach ((array) $class->externalForm as $extForm) {
            if (in_array($extForm, $class->avoidDuplication)) {
                continue;
            }
            $new_table = $extForm;
            $new_class = new $new_table();
            $reference_field = array_search($table, $new_class->references);
            $sql = 'SELECT * FROM ' . $new_table . ' WHERE ' . $reference_field . ' = ' . EC($main_item[$class->fieldid]);
            $rs = $new_class->getRecordSet($sql, MYSQLI_ASSOC);
            foreach ((array) $rs as $key => $r) {
                foreach ((array) $r as $field => $value) {
                    if ($field == $new_class->fieldid) {
                        $item[$new_table][$key]['itemid'] = '-1';
                        $item[$new_table][$key][$new_class->fieldid] = '-1';
                    } elseif ($field == $reference_field) {
                        $item[$new_table][$key][$field] = $duplicated_id;
                    } elseif (in_array($field, $new_class->literalFields)) {
                        $sql = 'SELECT literalLangFk,literalText 
                        FROM literal_groups 
                        LEFT JOIN literal_translations ON literalGroupId = literalGroupFk
                        WHERE itemLiteralId = ' . EC($value);
                        $translations = $class->getRecordSet($sql, MYSQLI_ASSOC);
                        foreach ((array) $translations as $translation) {

                            $item[$new_table][$key][$field][$translation ['literalLangFk']] = $translation['literalText'];
                        }
                        if (count($item[$new_table][$key][$field]) > 0) {
                            $item[$new_table][$key][$field]['itemLiteralId'] = '';
                        }
                    } else {
                        $item [$new_table][$key][$field] = $value;
                    }
                }
            }
            if (count($item[$new_table]) > 0) {
                if ($new_class->table == 'i tem_items') {
//$duplicated_id = $class->saveItem($item,false,false);
                } else {
                    $new_class->saveItem($item[$new_table], $reference_field, $duplicated_id);
                }
            }
        }
        return $duplicated_id;
    }

    function deleteItem($itemid) {
        $ok = false;
        $langs = new Literal_langs ();
        $literals = new Literal_groups();
        $all_langs = $langs->getAll('langId,langIso');
        foreach ((array) $this->imageFields as $imageField) {
            $sql = 'SELECT ' . $imageField . ' FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $itemid;
            $theImage = $this->getValue($sql);
            if (in_array($imageField, $this->literalFields)) {
                foreach ((array) $all_langs as $all_langs_values) {
                    $imageName = $literals->getLiteral($theImage, $all_langs_values['langIso']);
                    @unlink(BASE_PATH . $this->imgFolder . $imageName);
                    foreach ((array) $this->resize as $subfolder => $thumb) {
                        @unlink(BASE_PATH . $this->imgFolder . $subfolder . '/' . $imageName);
                    }
                }
            } else {
                @unlink(BASE_PATH . $this->imgFolder . $theImage);
                foreach ((array) $this->resize as $subfolder => $thumb) {
                    @unlink(BASE_PATH . $this->imgFolder . $subfolder . '/' . $theImage);
                }
            }
        }

        foreach ((array) $this->literalFields as $literal) {
            $delete = $this->getOne($itemid, $literal);
            $sql = 'SELECT literalGroupId FROM literal_groups WHERE itemLiteralId = ' .
                    $this->fridge($delete[$literal]);
//echo $sql . "\n";
            $id = $this->getValue($sql);
            $sql = 'DELETE FROM literal_groups WHERE itemLiteralId = ' . $this->fridge($delete[$literal]);
//echo $s
            ql . "\n";
            $this->query($sql);
            $sql = 'DELETE FROM literal_translations WHERE literalGroupFk = ' . $this->fridge($id);
//echo $sql . "\n";
            $this->query($sql);
        }

        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->fieldid . ' = ' . $this->fridge($itemid);
//echo $sql . DEBUG_ENTER;
        $ok = $this->query($sql);
        return $ok;
    }

}

?>