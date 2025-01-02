
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title">Setting Tables</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div class="card-block">
                                <h4 class="card-title">Hover Rows</h4>
								<div class="table-responsive">
									<table class="table table-hover mb-0">
										<thead>
											<tr>
												<th>Firstname</th>
												<th>Lastname</th>
												<th>Email</th>
											</tr>
										</thead>
										<tbody>
										
										<?php
											$sql = "SELECT * FROM dpa_setting";
											$res = mysqli_query($conn, $sql);
											//echo $res->num_rows;
											
											if($res->num_rows > 0){
											// output data of each row
												//while(($row = $res->fetch_array()) !==null){
												while($row = mysqli_fetch_array($res)){
										?>
												<tr>
													<td><?= $row[0] ?></td>
													<td><?= $row['type'] ?></td>
													<td><?= $row[2] ?></td>
												</tr>
											
										<?php
												}
											}else{
												echo "<tr>
														<td colspan='3'>No data found!</td>
													</tr>";
											}
											$conn->close();
										?>
										</tbody>
									</table>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                