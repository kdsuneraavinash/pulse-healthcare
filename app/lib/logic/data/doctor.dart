class Doctor {
  String accountId;
  String nic;
  String fullName;
  String displayName;
  String category;
  String slmcId;
  String email;
  String phoneNumber;

  Doctor({
    this.accountId,
    this.nic,
    this.fullName,
    this.displayName,
    this.category,
    this.slmcId,
    this.email,
    this.phoneNumber,
  });


  factory Doctor.fromMap(Map<String, String> map) {
    return Doctor(
      accountId: map['account_id'],
      nic: map['nic'],
      fullName: map['full_name'],
      displayName: map['display_name'],
      category: map['category'],
      slmcId: map['slmc_id'],
      email: map['email'],
      phoneNumber: map['phone_number'],
    );
  }
}
