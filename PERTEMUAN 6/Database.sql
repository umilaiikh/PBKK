USE master;
GO

IF DB_ID('StudentRegistrationDB') IS NOT NULL
BEGIN
    ALTER DATABASE StudentRegistrationDB
    SET SINGLE_USER WITH ROLLBACK IMMEDIATE;

    DROP DATABASE StudentRegistrationDB;
END;
GO

CREATE DATABASE StudentRegistrationDB;
GO

USE StudentRegistrationDB;
GO

CREATE TABLE Programs (
    ProgramId INT IDENTITY(1,1) PRIMARY KEY,
    ProgramCode NVARCHAR(10) NOT NULL UNIQUE,
    ProgramName NVARCHAR(100) NOT NULL,
    IsActive BIT NOT NULL DEFAULT 1,
    CreatedAt DATETIME NOT NULL DEFAULT GETDATE()
);
GO
CREATE TABLE Students (
    StudentId INT IDENTITY(1,1) PRIMARY KEY,
    NIM NVARCHAR(30) NOT NULL UNIQUE,
    Name NVARCHAR(150) NOT NULL,
    ProgramId INT NOT NULL,
    Gender NVARCHAR(20) NULL,
    BirthDate DATE NULL,
    Address NVARCHAR(500) NULL,
    PhoneNumber NVARCHAR(30) NULL,
    CreatedAt DATETIME NOT NULL DEFAULT GETDATE(),
    UpdatedAt DATETIME NULL,

    CONSTRAINT FK_Students_Programs
        FOREIGN KEY (ProgramId)
        REFERENCES Programs(ProgramId)
);
GO

INSERT INTO Programs (ProgramCode, ProgramName)
VALUES
    ('TI', 'Teknik Informatika'),
    ('SI', 'Sistem Informasi'),
    ('MNJ', 'Manajemen'),
    ('TE', 'Teknik Elektro'),
    ('DKV', 'Desain Komunikasi Visual');
GO

INSERT INTO Students
    (NIM, Name, ProgramId, Gender, BirthDate, Address, PhoneNumber)
VALUES
    ('5025241001', 'Andi Pratama', 1, 'Laki-laki', '2005-01-15', 'Surabaya', '081234567801'),
    ('5025241002', 'Siti Aminah', 2, 'Perempuan', '2005-03-20', 'Sidoarjo', '081234567802'),
    ('5025241003', 'Budi Santoso', 3, 'Laki-laki', '2004-07-12', 'Gresik', '081234567803');
GO

SELECT
    s.StudentId,
    s.NIM,
    s.Name,
    p.ProgramCode,
    p.ProgramName,
    s.Gender,
    s.BirthDate,
    s.Address,
    s.PhoneNumber,
    s.CreatedAt,
    s.UpdatedAt
FROM Students AS s
INNER JOIN Programs AS p
    ON s.ProgramId = p.ProgramId
ORDER BY s.StudentId;
GO